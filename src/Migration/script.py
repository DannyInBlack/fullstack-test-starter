import json
import mysql.connector

# Load JSON data
with open('/path/to/junior-web-dev.json') as f:
    data = json.load(f)

# Connect to MySQL
conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='test'
)
cursor = conn.cursor()

# Insert categories
for category in data['data']['categories']:
    cursor.execute(
        "INSERT INTO categories (name, typename) VALUES (%s, %s)",
        (category['name'], category['__typename'])
    )

# Insert products and related data
for product in data['data']['products']:
    cursor.execute(
        "INSERT INTO products (id, name, inStock, description, category, brand, typename) VALUES (%s, %s, %s, %s, %s, %s, %s)",
        (product['id'], product['name'], product['inStock'], product['description'], product['category'], product['brand'], product['__typename'])
    )
    
    # Insert gallery images
    for image in product['gallery']:
        cursor.execute(
            "INSERT INTO galleries (product_id, image_url) VALUES (%s, %s)",
            (product['id'], image)
        )
    
    # Insert attributes and attribute items
    for attribute in product['attributes']:
        cursor.execute(
            "INSERT INTO attributes (product_id, attribute_id, name, type, typename) VALUES (%s, %s, %s, %s, %s)",
            (product['id'], attribute['id'], attribute['name'], attribute['type'], attribute['__typename'])
        )
        attribute_id = cursor.lastrowid
        for item in attribute['items']:
            cursor.execute(
                "INSERT INTO attribute_items (attribute_id, displayValue, value, typename) VALUES (%s, %s, %s, %s)",
                (attribute_id, item['displayValue'], item['value'], item['__typename'])
            )
    
    # Insert prices
    for price in product['prices']:
        cursor.execute(
            "INSERT INTO prices (product_id, amount, currency_label, currency_symbol, typename) VALUES (%s, %s, %s, %s, %s)",
            (product['id'], price['amount'], price['currency']['label'], price['currency']['symbol'], price['__typename'])
        )

# Commit the transaction
conn.commit()

# Close the connection
cursor.close()
conn.close()