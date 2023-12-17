import requests
from bs4 import BeautifulSoup
import pymongo
import time

# Connect to MongoDB
client = pymongo.MongoClient("mongodb://localhost:27017/")
db = client["stocks"]
collection = db["most_active"]

def getData():
    url = "https://finance.yahoo.com/most-active"
    try:
        response = requests.get(url)
        response.raise_for_status()
    except requests.exceptions.RequestException as e:
        print(f"Error: {e}")
        return None

    soup = BeautifulSoup(response.text, 'html.parser')

    stocks = []
    for row in soup.select('table tbody tr'):
        columns = row.find_all('td')
        symbol, name, price, change, volume = [col.text.strip() for col in columns[:5]]
        stocks.append({'Symbol': symbol, 'Name': name, 'Price': price, 'Change': change, 'Volume': volume})

    return stocks

def updateDB():
    stocks = getData()
    if stocks:
        collection.insert_many(stocks)

while True:
    updateDB()
    time.sleep(180) 