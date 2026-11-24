#!/usr/bin/env python3
"""Fetch libraries from OpenStreetMap (Overpass) for one or more cities and save to CSV.

This version uses only Python's standard library (no pip installs required).

Usage:
  python osm_libraries_to_csv.py "Alwar,Jaipur" "India"
"""
import sys
import time
import json
import csv
import urllib.parse
import urllib.request
from typing import List, Dict, Any, Optional

OVERPASS_URL = "https://overpass-api.de/api/interpreter"
NOMINATIM_URL = "https://nominatim.openstreetmap.org/search"

def post_overpass(query: str, timeout: int = 90) -> Dict[str, Any]:
    data = query.encode("utf-8")
    req = urllib.request.Request(OVERPASS_URL, data=data, method="POST")
    req.add_header("Content-Type", "application/x-www-form-urlencoded")
    req.add_header("User-Agent", "osm-libraries-csv/1.0")
    try:
        with urllib.request.urlopen(req, timeout=timeout) as resp:
            raw = resp.read()
            try:
                return json.loads(raw.decode('utf-8'))
            except Exception as e:
                # Save raw response for debugging
                try:
                    with open('tools/overpass_response_error.txt', 'wb') as fh:
                        fh.write(raw)
                except Exception:
                    pass
                raise RuntimeError("Failed to decode Overpass JSON response. Raw response written to tools/overpass_response_error.txt") from e
    except urllib.error.HTTPError as he:
        # Save error response body if available
        try:
            body = he.read()
            with open('tools/overpass_response_error.txt', 'wb') as fh:
                fh.write(body)
        except Exception:
            pass
        raise

def nominatim_search(q: str, timeout: int = 30) -> Optional[Dict[str, Any]]:
    params = urllib.parse.urlencode({"q": q, "format": "json", "limit": 1})
    url = NOMINATIM_URL + "?" + params
    req = urllib.request.Request(url, headers={"User-Agent": "osm-libraries-csv/1.0"})
    with urllib.request.urlopen(req, timeout=timeout) as resp:
        data = json.load(resp)
        return data[0] if data else None

def get_area_by_name(city: str, country: Optional[str] = None) -> Optional[Any]:
    q = f'area["name"="{city}"];out;'
    try:
        resp = post_overpass(q)
        if resp.get("elements"):
            return resp["elements"][0]["id"]
    except Exception:
        pass
    # fallback to nominatim bbox
    try:
        res = nominatim_search(city + (f", {country}" if country else ""))
        if not res:
            return None
        return {"bbox": res.get("boundingbox")}
    except Exception:
        return None

def query_overpass_for_libraries(area_result: Any) -> Dict[str, Any]:
    if isinstance(area_result, dict) and "bbox" in area_result:
        s, n, w, e = area_result["bbox"]
        bbox = f"{s},{w},{n},{e}"
        query = f"""
        (
          node["amenity"="library"]({bbox});
          way["amenity"="library"]({bbox});
          relation["amenity"="library"]({bbox});
        );
        out center tags;
        """
    else:
        area_id = area_result
        query = f"""
        area({area_id})->.searchArea;
        (
          node["amenity"="library"](area.searchArea);
          way["amenity"="library"](area.searchArea);
          relation["amenity"="library"](area.searchArea);
        );
        out center tags;
        """
    return post_overpass(query)

def extract_places(osm_json: Dict[str, Any], city_label: str) -> List[Dict[str, Any]]:
    out = []
    for el in osm_json.get("elements", []):
        tags = el.get("tags", {})
        name = tags.get("name")
        if not name:
            continue
        if el.get("type") == "node":
            lat = el.get("lat")
            lon = el.get("lon")
        else:
            center = el.get("center", {})
            lat = center.get("lat")
            lon = center.get("lon")
        out.append({
            "query_city": city_label,
            "name": name,
            "osm_type": el.get("type"),
            "osm_id": el.get("id"),
            "lat": lat,
            "lon": lon,
            "street": tags.get("addr:street") or tags.get("street"),
            "housenumber": tags.get("addr:housenumber"),
            "neighbourhood": tags.get("addr:neighbourhood"),
            "city": tags.get("addr:city") or tags.get("is_in"),
            "postcode": tags.get("addr:postcode"),
            "phone": tags.get("phone") or tags.get("contact:phone"),
            "website": tags.get("website") or tags.get("url"),
            "opening_hours": tags.get("opening_hours"),
            "wikidata": tags.get("wikidata"),
            "wikipedia": tags.get("wikipedia"),
            "description": tags.get("description") or tags.get("note"),
            "source": tags.get("source"),
        })
    return out

def save_csv(rows: List[Dict[str, Any]], filename: str) -> int:
    if not rows:
        # create header-only file
        headers = [
            "query_city","name","osm_type","osm_id","lat","lon","street","housenumber",
            "neighbourhood","city","postcode","phone","website","opening_hours","wikidata",
            "wikipedia","description","source"
        ]
        with open(filename, "w", newline='', encoding="utf-8") as f:
            writer = csv.writer(f)
            writer.writerow(headers)
        return 0
    headers = list(rows[0].keys())
    with open(filename, "w", newline='', encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=headers)
        writer.writeheader()
        for r in rows:
            writer.writerow(r)
    return len(rows)

def parse_cities_arg(arg: str) -> List[str]:
    return [p.strip() for p in arg.replace(";", ",").split(",") if p.strip()]

def fetch_multiple_cities(cities: List[str], country: Optional[str] = None, out_filename: Optional[str] = None):
    all_rows = []
    for i, city in enumerate(cities):
        print(f"Processing: {city}")
        area = get_area_by_name(city, country)
        if area is None:
            print(f"  Could not find area for {city}, skipping")
            continue
        osm_json = query_overpass_for_libraries(area)
        rows = extract_places(osm_json, city)
        print(f"  Found {len(rows)} libraries in {city}")
        all_rows.extend(rows)
        if i < len(cities)-1:
            time.sleep(2)
    if not out_filename:
        safe = "_".join([c.replace(" ", "_") for c in cities]).lower()
        out_filename = f"libraries_combined_{safe}.csv"
    count = save_csv(all_rows, out_filename)
    return count, out_filename

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python osm_libraries_to_csv.py \"City1,City2\" [Country]")
        sys.exit(1)
    cities_arg = sys.argv[1]
    country = sys.argv[2] if len(sys.argv) > 2 else None
    cities = parse_cities_arg(cities_arg)
    print("Fetching for:", cities, "country:", country)
    count, fname = fetch_multiple_cities(cities, country)
    print(f"Saved {count} rows to {fname}")
