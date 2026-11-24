import time
from urllib.parse import urljoin

from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from openpyxl import Workbook

BASE = 'https://alwar.idbf.in'
LISTING_PATH = '/library'
LIMIT = 30


def create_driver(headless=True):
    options = webdriver.ChromeOptions()
    if headless:
        options.add_argument('--headless=new')
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--disable-gpu')
    options.add_argument('--window-size=1920,1080')

    service = Service(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service, options=options)
    return driver


def get_listing_links(driver, limit=LIMIT):
    url = urljoin(BASE, LISTING_PATH)
    driver.get(url)
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, 'body')))
    time.sleep(1)

    soup = BeautifulSoup(driver.page_source, 'html.parser')
    links = []
    for a in soup.find_all('a', href=True):
        href = a['href']
        if '/library/' in href:
            full = urljoin(BASE, href)
            if full not in links:
                links.append(full)
        if len(links) >= limit:
            break
    return links[:limit]


def parse_detail(driver, url):
    driver.get(url)
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, 'body')))
    time.sleep(0.8)

    soup = BeautifulSoup(driver.page_source, 'html.parser')
    text = soup.get_text(separator=' ', strip=True)

    # Name
    name = None
    for tag in ('h1', 'h2', 'h3'):
        t = soup.find(tag)
        if t and t.get_text(strip=True):
            name = t.get_text(strip=True)
            break

    # Image
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
    import re
    for m in re.findall(r'\b\d{10}\b', text):
        phones.add(m)
    phone = ', '.join(sorted(phones)) if phones else ''

    # Price
    import re as _re
    price_match = _re.search(r'(?:₹|Rs\.?)[\s\-]*[0-9,]+', text)
    price = price_match.group(0) if price_match else ''

    # Address
    address = ''
    addr_tag = soup.find(class_=_re.compile('address|location|addr|place', _re.I))
    if addr_tag:
        address = addr_tag.get_text(separator=' ', strip=True)
    else:
        for p in soup.find_all('p'):
            pt = p.get_text(' ', strip=True)
            if 'Address' in pt or 'address' in pt or 'Location' in pt:
                address = pt
                break

    return {
        'name': name or '',
        'phone': phone,
        'price': price,
        'image_link': img or '',
        'address': address,
        'detail_url': url,
    }


def save_to_excel(rows, out='alwar_libraries_30.xlsx'):
    wb = Workbook()
    ws = wb.active
    headers = ['name', 'phone', 'price', 'image_link', 'address', 'detail_url']
    ws.append(headers)
    for r in rows:
        ws.append([r.get(h, '') for h in headers])
    wb.save(out)


def main():
    driver = create_driver(headless=True)
    try:
        links = get_listing_links(driver, limit=LIMIT)
        print(f'Found {len(links)} links')
        rows = []
        for i, link in enumerate(links, 1):
            print(f'[{i}/{len(links)}] {link}')
            rows.append(parse_detail(driver, link))
            time.sleep(0.8)

        if rows:
            save_to_excel(rows)
            print('Saved to alwar_libraries_30.xlsx')
        else:
            print('No rows scraped')
    finally:
        driver.quit()


if __name__ == '__main__':
    main()
