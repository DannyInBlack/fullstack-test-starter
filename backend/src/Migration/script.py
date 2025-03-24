import json
import mysql.connector

# Load JSON data
with open('src/Migration/junior-web-dev.json') as f:
    data = json.load(f)

# Connect to MySQL
conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='test'
)
cursor = conn.cursor()

itemsMap = {}

# Insert categories
for category in data['data']['categories']:
    cursor.execute(
        "INSERT INTO categories (name) VALUES (%s)",
        (category['name'],)
    )

# Insert products and related data
for product in data['data']['products']:
    cursor.execute(
        "INSERT INTO products (id, name, inStock, description, category, brand) VALUES (%s, %s, %s, %s, %s, %s)",
        (product['id'], product['name'], product['inStock'], product['description'], product['category'], product['brand'])
    )
    
    # Insert gallery images
    for image in product['gallery']:
        cursor.execute(
            "INSERT INTO galleries (product_id, image_url) VALUES (%s, %s)",
            (product['id'], image)
        )
    
    # Insert attribute sets and attribute items
    for attribute in product['attributes']:
        cursor.execute(
            "INSERT INTO attribute_sets (product_id, id, name, type) VALUES (%s, %s, %s, %s)",
            (product['id'], attribute['id'], attribute['name'], attribute['type'])
        )
        attribute_set_id = cursor.lastrowid
        for item in attribute['items']:

            if itemsMap.get(item['id'], 0) == 0:
                cursor.execute(
                    "INSERT INTO attributes (id, displayValue, value) VALUES (%s, %s, %s)",
                    (item['id'], item['displayValue'], item['value'])
                )

                itemsMap[item['id']] = 1

            cursor.execute(
                "INSERT INTO attributes_attribute_sets (attribute_set_id, attribute_id) VALUES (%s, %s)",
                (attribute_set_id, item['id'])
            )
    
    # Insert prices
    for price in product['prices']:
        cursor.execute(
            "INSERT INTO prices (product_id, amount) VALUES (%s, %s)",
            (product['id'], price['amount'])
        )
        price_id = cursor.lastrowid
        cursor.execute(
            "INSERT INTO currencies (price_id, label, symbol) VALUES (%s, %s, %s)",
            (price_id, price['currency']['label'], price['currency']['symbol'])
        )


# Commit the transaction
conn.commit()

print("Successfully imported data")

# Close the connection
cursor.close()
conn.close()