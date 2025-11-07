import sqlite3
import os

# Define database path
db_path = "domain_names.db"

# Remove existing database if it exists
if os.path.exists(db_path):
    os.remove(db_path)
    print(f"Removed existing database: {db_path}")

# Read SQL file
with open("domain_names_sqlite3.sql", "r", encoding="utf-8") as f:
    sql_script = f.read()

# Create connection and execute SQL
try:
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    # Execute the entire SQL script
    cursor.executescript(sql_script)

    conn.commit()
    print(f"Database created successfully: {db_path}")

    # Verify tables were created
    cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
    tables = cursor.fetchall()
    print(f"\nTables created:")
    for table in tables:
        print(f"  - {table[0]}")

    # Show record counts
    cursor.execute("SELECT COUNT(*) FROM pays;")
    pays_count = cursor.fetchone()[0]
    print(f"\nRecords in 'pays' table: {pays_count}")

except sqlite3.Error as e:
    print(f"Error creating database: {e}")
finally:
    if conn:
        conn.close()
