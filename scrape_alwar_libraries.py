import re
import time
from urllib.parse import urljoin

import requests
import csv
from bs4 import BeautifulSoup

BASE = 'https://alwar.idbf.in'
LISTING_PATH = '/library'

session = requests.Session()
session.headers.update({'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0 Safari/537.36'})


def get(url, allow_redirects=True):
    try:
        r = session.get(url, timeout=15, allow_redirects=allow_redirects)
    except Exception as e:
        print(f"Request error for {url}: {e}")
        return None

    # Some pages set a small JS cookie and reload. Emulate that if detected.
    if r is not None and 'js_cookie_set' in (r.text or ''):
        # set cookie both for the domain and the exact host
        session.cookies.set('null', 'null', domain='.idbf.in')
        session.cookies.set('null', 'null', domain='alwar.idbf.in')
        try:
            r = session.get(url, timeout=15, allow_redirects=allow_redirects)
        except Exception as e:
            print(f"Second request error for {url}: {e}")
            return None

    return r


def find_library_links(limit=30):
    url = urljoin(BASE, LISTING_PATH)
    r = get(url)
    if not r or r.status_code != 200:
        raise RuntimeError(f"Failed to fetch listing page: {url} (status={(r.status_code if r else 'no response')})")

    soup = BeautifulSoup(r.text, 'html.parser')
    links = []

    # Find anchors that look like library detail links
    for a in soup.find_all('a', href=True):
        href = a['href']
        if '/library/' in href:
            full = urljoin(BASE, href)
            if full not in links:
                links.append(full)
        if len(links) >= limit:
            break

    return links[:limit]


PHONE_RE = re.compile(r'\b\d{10}\b')
PRICE_RE = re.compile(r'(?:₹|Rs\.?)[\s\-]*[0-9,]+')


def parse_detail(url):
    r = get(url)
    if not r or r.status_code != 200:
        print(f"Failed to fetch detail: {url}")
        return None

    soup = BeautifulSoup(r.text, 'html.parser')
    text = soup.get_text(separator=' ', strip=True)

    # Name
    name = None
    for tag in ('h1', 'h2', 'h3'):
        t = soup.find(tag)
        if t and t.get_text(strip=True):
            name = t.get_text(strip=True)
            break

    # Image - prefer og:image
    img = None
    og = soup.find('meta', property='og:image')
    if og and og.get('content'):
        img = urljoin(BASE, og['content'])
    else:
        img_tag = soup.find('img')
        if img_tag and img_tag.get('src'):
            img = urljoin(BASE, img_tag['src'])

    # Phone
    phones = set()
    for a in soup.find_all('a', href=True):
        if a['href'].startswith('tel:'):
            phones.add(a['href'].split(':', 1)[1].strip())
    for m in PHONE_RE.findall(text):
        phones.add(m)
    phone = ', '.join(sorted(phones)) if phones else None

    # Price
    price = None
    m = PRICE_RE.search(text)
    if m:
        price = m.group(0)

    # Address
    address = None
    addr_tag = soup.find(class_=re.compile('address|location|addr|place', re.I))
    if addr_tag:
        address = addr_tag.get_text(separator=' ', strip=True)
    else:
        # fallback: look for <p> tags containing common address words
        for p in soup.find_all('p'):
            pt = p.get_text(' ', strip=True)
            if 'Address' in pt or 'address' in pt or 'Location' in pt:
                address = pt
                break

    return {
        'name': name,
        'phone': phone,
        'price': price,
        'image_link': img,
        'address': address,
        'detail_url': url,
    }


def main():
    print('Finding library links...')
    links = find_library_links(limit=30)
    print(f'Found {len(links)} links')

    results = []
    for i, link in enumerate(links, 1):
        print(f'[{i}/{len(links)}] Parsing {link}')
        data = parse_detail(link)
        if data:
            results.append(data)
        time.sleep(1)

    if not results:
        print('No results found.')
        return

    out = 'alwar_libraries_30.csv'
    keys = ['name', 'phone', 'price', 'image_link', 'address', 'detail_url']
    with open(out, 'w', newline='', encoding='utf-8') as f:
        writer = csv.DictWriter(f, fieldnames=keys)
        writer.writeheader()
        for row in results:
            writer.writerow({k: (row.get(k) or '') for k in keys})

    print(f'Wrote {len(results)} rows to {out}')


if __name__ == '__main__':
    main()
