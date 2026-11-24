#!/usr/bin/env python3
"""Fetch libraries from OpenStreetMap (Overpass) for one or more cities and save to Excel.

Usage:
  python osm_libraries_to_excel.py "Alwar,Jaipur" "India"

This script is free to use (OpenStreetMap / Overpass). It queries each city and
outputs a combined Excel file with extra fields.
"""
import requests
import pandas as pd
import sys
import time
from typing import List, Dict, Any, Optional

OVERPASS_URL = "https://overpass-api.de/api/interpreter"
NOMINATIM_URL = "https://nominatim.openstreetmap.org/search"

def get_area_by_name(city: str, country: Optional[str] = None) -> Optional[Any]:
    # Try Overpass area lookup first
    q = f'area["name"="{city}"];out;'
    try:
        resp = requests.post(OVERPASS_URL, data={"data": q}, timeout=60)
        resp.raise_for_status()
        data = resp.json()
        if data.get("elements"):
            return data["elements"][0]["id"]
    except Exception:
        pass
    # Fallback to Nominatim bbox
    params = {"q": city + (f", {country}" if country else ""), "format": "json", "limit": 1}
    headers = {"User-Agent": "osm-libraries-script/1.0 (+https://example.com)"}
    r = requests.get(NOMINATIM_URL, params=params, headers=headers, timeout=30)
    r.raise_for_status()
    results = r.json()
    if not results:
        return None
    bbox = results[0].get("boundingbox")
    return {"bbox": bbox}

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
    resp = requests.post(OVERPASS_URL, data={"data": query}, timeout=180)
    resp.raise_for_status()
    return resp.json()

def extract_places(osm_json: Dict[str, Any], city_label: str) -> List[Dict[str, Any]]:
    places: List[Dict[str, Any]] = []
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
        place = {
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
            "fax": tags.get("fax"),
            "email": tags.get("email"),
            "website": tags.get("website") or tags.get("url") or tags.get("contact:website"),
            "operator": tags.get("operator"),
            "opening_hours": tags.get("opening_hours"),
            "wikidata": tags.get("wikidata"),
            "wikipedia": tags.get("wikipedia"),
            "description": tags.get("description") or tags.get("note"),
            "source": tags.get("source"),
        }
        places.append(place)
    return places

def save_to_excel(places: List[Dict[str, Any]], filename: str) -> int:
    df = pd.DataFrame(places)
    df.to_excel(filename, index=False)
    return len(df)

def fetch_multiple_cities(cities: List[str], country: Optional[str] = None, out_filename: Optional[str] = None) -> (int, str):
    all_places: List[Dict[str, Any]] = []
    for i, city in enumerate(cities):
        city = city.strip()
        print(f"Processing city: {city}")
        area = get_area_by_name(city, country)
        if area is None:
            print(f"  Could not find area for {city}, skipping.")
            continue
        osm_json = query_overpass_for_libraries(area)
        places = extract_places(osm_json, city)
        print(f"  Found {len(places)} libraries in {city}")
        all_places.extend(places)
        # Be polite to public Overpass servers
        if i < len(cities) - 1:
            time.sleep(3)
    if not out_filename:
        safe = "_".join([c.replace(" ", "_") for c in cities]).lower()
        out_filename = f"libraries_combined_{safe}.xlsx"
    count = save_to_excel(all_places, out_filename)
    return count, out_filename

def parse_cities_arg(arg: str) -> List[str]:
    # Accept comma-separated or semicolon-separated
    parts = [p.strip() for p in arg.replace(";", ",").split(",") if p.strip()]
    return parts

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python osm_libraries_to_excel.py \"City1,City2\" [Country]")
        sys.exit(1)
    cities_arg = sys.argv[1]
    country = sys.argv[2] if len(sys.argv) > 2 else None
    cities = parse_cities_arg(cities_arg)
    print("Fetching libraries for:", cities, "country:", country)
    count, filename = fetch_multiple_cities(cities, country)
    print(f"Saved {count} rows to {filename}")
