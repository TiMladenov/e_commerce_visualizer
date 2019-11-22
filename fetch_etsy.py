import requests
import time
import sqlite3
from datetime import datetime
import re
from pprint import pprint

class Product(object) :
    """
    A class used to store the data for each returned item from Etsy's API.

    Methods
    -------
    __init__()
        The initializing function of the class.
    __del__()
        The destructor of the class.
    set_shop_name(_name)
        Sets the name of the shop
    get_shop_name()
        Returns the name of the shop
    set_product_id(_id)
        Sets the ID of the product
    get_product_id()
        Returns the ID of the product
    set_product_name(_name)
        Sets the product name
    get_product_name()
        Returns the product name
    set_product_category(_category)
        Sets the product category
    get_product_category()
        Returns the product category
    set_product_descr(_descr)
        Sets the product description
    get_product_descr()
        Returns the product description
    set_product_img(_img)
        Sets the IMG url of the product
    get_product_image()
        Returns the IMG url of the product
    set_product_price(_price)
        Sets the price of the product
    get_product_price()
        Returns the price of the product
    set_price_curr(_curr)
        Sets the price currency of the product's price
    get_price_curr()
        Returns the price currency of the product's price
    set_is_promo(_promo)
        Sets boolean denoting if the product is promo
    get_is_promo()
        Returns boolean denoting if the product is promo
    set_is_limited(_limited)
        Sets boolean denoting if the product is limited
    get_is_limited()
        Returns boolean denoting if the product is limited
    set_product_shop_url(_url)
        Sets the product's URL on shop's website
    get_product_shop_url()
        Returns product's URL on shop's website
    set_valid_until(_valid_date)
        Sets valid until of the listing, Unix time
    get_valid_until()
        Gets the valid until of the listing, Unix time
    set_product_date_added_db(_date)
        Sets the date of adding the product to local DB, Unix time
    get_product_date_added_db()
        Returns the date of adding the product to local DB, Unix time
    """
    def __init__(self):
        """
        Parameters
        ----------
        shop_name : str
            The name of the shop.
        product_id : int
            The product_id of the item.
        product_name : str
            The name of the item.
        product_category : str
            The category of the item.
        product_descr : str
            The description of the item.
        product_image : str
            URL to the image of the item.
        product_price : float
            The price of the item.
        product_price_curr : str
            The currency of the price of the item.
        product_is_promo : boolean
            If the product is in promo. 0 - False, 1 - True.
        product_is_limited : boolean
            If the product is limited. 0 - False, 1 - True.
        product_shop_url : str
            URL to item's page on shop's website.
        product_valid_until : int
            UNIX time valid until (product listing on shop's website).
        product_date_added_db : int
            UNIX time of the item being added to the local DB.
        """
        self.shop_name = None
        self.product_id = 0
        self.product_name = None
        self.product_category = None
        self.product_descr = None
        self.product_image = None
        self.product_price = 0
        self.product_price_curr = None
        self.product_is_promo = 0
        self.product_is_limited = 0
        self.product_shop_url = None
        self.product_valid_until = 0
        self.product_date_added_db = 0

    def set_shop_name(self, _name):
        self.shop_name = _name

    def get_shop_name(self):
        return self.shop_name

    def set_product_id(self, _id):
        self.product_id = _id

    def get_product_id(self):
        return self.product_id

    def set_product_name(self, _name):
        self.product_name = _name

    def get_product_name(self):
        return self.product_name

    def set_product_category(self, _category):
        self.product_category = _category

    def get_product_category(self):
        return self.product_category

    def set_product_descr(self, _descr):
        self.product_descr = _descr

    def get_product_descr(self):
        return self.product_descr

    def set_product_img(self, _img):
        self.product_image = _img

    def get_product_image(self):
        return self.product_image

    def set_product_price(self, _price):
        self.product_price = _price

    def get_product_price(self):
        return self.product_price

    def set_price_curr(self, _curr):
        self.product_price_curr = _curr

    def get_price_curr(self):
        return self.product_price_curr

    def set_is_promo(self, _promo):
        self.product_is_promo = _promo

    def get_is_promo(self):
        return self.product_is_promo

    def set_is_limited(self, _limited):
        self.product_is_limited = _limited

    def get_is_limited(self):
        return self.product_is_limited

    def set_product_shop_url(self, _url):
        self.product_shop_url = _url

    def get_product_shop_url(self):
        return self.product_shop_url

    def set_valid_until(self, _valid_date):
        self.product_valid_until = _valid_date

    def get_valid_until(self):
        return self.product_valid_until

    def set_product_date_added_db(self, _date):
        self.product_date_added_db = _date

    def get_product_date_added_db(self):
        return self.product_date_added_db

    def __del__(self):
        del self

def print_error():
    print('Error writing to DB. Exiting.')

def populate_tracking_db(_propery_list):
    # Populates the new data into the tracking database table
    db_conn = sqlite3.connect("e_commerce.db")
    db_cursor = db_conn.cursor()

    e_product_visits_list = list()

    property = list()
    #inserts id, name, category, shop, price, price currency
    #to a list that will try to insert data into e_product_visit_table

    property.append(_propery_list[0][0])
    property.append(_propery_list[0][2])
    property.append(_propery_list[0][3])
    property.append(_propery_list[0][1])
    property.append(_propery_list[0][6])
    property.append(_propery_list[0][7])
    e_product_visits_list.append(property)

    db_job = 'INSERT INTO e_product_visit_data (product_id,product_name,product_category,shop_name,price,price_curr)\
        SELECT ?,?,?,?,?,? \
            WHERE NOT EXISTS (SELECT product_id FROM e_product_visits WHERE product_id = {})'.format(e_product_visits_list[0][0])
    try:
        print("Inserting into tracking db...")

        db_cursor.executemany(db_job, e_product_visits_list)
        db_conn.commit()

        # db_job = 'INSERT INTO e_product_visits_unique (product_id, product_name, product_category)\
        # SELECT ?,?,? \
        #     WHERE NOT EXISTS (SELECT product_id FROM e_product_visits_unique WHERE product_id = {})'.format(e_product_visits_unique_list[0][0])

        # db_cursor.executemany(db_job, e_product_visits_unique_list)
        # db_conn.commit()
        
        return True
    except Exception as e:
        print('Error inserting db entry due to:\n', str(e))
        return False

def insert_into_db(_propery_list):
    # Inserts all new rows with data fetched from Etsy's API into the
    # product DB.
    db_conn = sqlite3.connect('e_commerce.db')
    db_cursor = db_conn.cursor()
    db_job = 'INSERT INTO e_product (\
        product_id,shop_name,product_name,product_category,\
        product_description,product_image,product_price,\
        product_price_curr,product_is_promo,product_is_limited,\
        product_shop_url,product_valid_until,product_date_added)\
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)'
    try:
        print('Inserting into db...')
        db_cursor.executemany(db_job, _propery_list)
        db_conn.commit()
        populate_tracking_db(_propery_list)
        return True
    except Exception as e:
        print('Error inserting db entry due to:\n', str(e))
        return False

def update_into_db(_db_obj_property, _db_obj_value, _product_id):
    # Updates specific property + value in the DB for a specific product_id
    db_conn = sqlite3.connect('e_commerce.db')
    db_cursor = db_conn.cursor()
    db_job = 'UPDATE e_product SET {} = \'{}\' WHERE product_id = {}'.format(_db_obj_property, _db_obj_value, _product_id)
    try:
        print('Updating db...')
        db_cursor.execute(db_job)
        db_conn.commit()
        return True
    except Exception as e:
        print('Error updating db entry due to:\n', str(e))
        return False

def check_product_db(_product_id):
    # Checks if a product with such _product_id exists in the DB, returns True or False.
    db_conn = sqlite3.connect('e_commerce.db')
    db_cursor = db_conn.cursor()
    db_job = 'SELECT product_id FROM e_product WHERE product_id = {}'.format(_product_id)
    try:
        print('Checking db...')
        db_cursor.execute(db_job)
        result = db_cursor.fetchone()
        if result:
            if result == '':
                return False
            else:
                return True
        else:
            return False
    except Exception as e:
        print('Error checking db due to:\t', str(e))
        return False


def push_object_data_to_db(obj_list):
    properties_list = []
    for obj in obj_list:
        property = []

        is_existing_in_db = check_product_db(obj.get_product_id())
        # If this product_id exists in the DB, update all its properties.
        if is_existing_in_db != False:

            prod_name = obj.get_product_name()
            # removes any special characters. Special characters freeze SQLite3's insert/update.
            prod_name = re.sub('[^A-Za-z0-9,.@]+', " ", obj.get_product_name())

            update = update_into_db('product_name', prod_name, obj.get_product_id())
            if update == False:
                print_error()
                break

            update = update_into_db('shop_name', obj.get_shop_name(), obj.get_product_id())
            if update == False:
                print_error()
                break

            update = update_into_db('product_category', obj.get_product_category(), obj.get_product_id())
            if update == False:
                print_error()
                break

            prod_descr = obj.get_product_descr()
            prod_descr = re.sub('[^A-Za-z0-9,.]+', " ", obj.get_product_descr())

            update = update_into_db('product_description', prod_descr, obj.get_product_id()) 
            if update == False:
                print_error()
                break

            update = update_into_db('product_image', obj.get_product_image(), obj.get_product_id()) 
            if update == False:
                print_error()
                break

            update = update_into_db('product_price', obj.get_product_price(), obj.get_product_id())
            if update == False:
                print_error()
                break

            update = update_into_db('product_price_curr', obj.get_price_curr(), obj.get_product_id())
            if update == False:
                print_error()
                break

            update = update_into_db('product_is_promo', obj.get_is_promo(), obj.get_product_id())
            if update == False:
                print_error()
                break

            update = update_into_db('product_is_limited', obj.get_is_limited(), obj.get_product_id())
            if update == False:
                print_error()
                break

            update = update_into_db('product_shop_url', obj.get_product_shop_url(), obj.get_product_id())
            if update == False:
                print_error()
                break

            update = update_into_db('product_valid_until', obj.get_valid_until(), obj.get_product_id())
            if update == False:
                print_error()
                break
            
            update = update_into_db('product_date_added', obj.get_product_date_added_db(), obj.get_product_id())
            if update == False:
                print_error()
                break
            continue
        # IF this product_id DOES NOT exist in the DB, get the data from each
        # 'Product' object into a list, which then add to a parent list and send it
        # for insertion in 'insert_into_db'.
        else:
            property.append(obj.get_product_id())
            property.append(obj.get_shop_name())
            prod_name = obj.get_product_name()
            prod_name = re.sub('[^A-Za-z0-9,.]+', " ", obj.get_product_name())
            # if prod_name.isalpha():
            #     prod_name = re.sub('[^A-Za-z0-9,.]+', " ", obj.get_product_name())
            # else:
            #     prod_name = str(obj.get_product_name())
            #     tmp_copy = prod_name
            #     prod_name = re.sub('[^A-Za-z0-9,.]+', " ", tmp_copy)
            property.append(prod_name)
            property.append(obj.get_product_category())
            prod_descr = obj.get_product_descr()
            prod_descr = re.sub('[^A-Za-z0-9,.]+', " ", obj.get_product_descr())
            # if prod_descr.isalpha():
            #     prod_descr = re.sub('[^A-Za-z0-9,.]+', " ", obj.get_product_descr())
            # else:
            #     prod_descr = str(obj.get_product_name())
            #     tmp_descr = prod_descr
            #     prod_descr = re.sub('[^A-Za-z0-9,.]+', " ", tmp_descr)
            property.append(prod_descr)
            property.append(obj.get_product_image())
            property.append(obj.get_product_price())
            property.append(obj.get_price_curr())
            property.append(obj.get_is_promo())
            property.append(obj.get_is_limited())
            property.append(obj.get_product_shop_url())
            property.append(obj.get_valid_until())
            property.append(obj.get_product_date_added_db())
            properties_list.append(property)

            insert_into_db(properties_list)
            properties_list.clear()
        
        
        
def start(URL, HEADERS):
    # Makes a call to Etsy's API. Creates an instance of 'Product' class
    # and structures the data in it for each returned entry in the JSON.
    # Adds the 'Product' class instance to 'product_obj_list', which then
    # it passes to 'push_object_data_to_db' to push the data to DB.
    product_obj_list = []
    req = requests.get(URL)
    if req.status_code == 200:
        json = req.json()
        product = ''

        for item in json['results']:
            product = Product()
            product.set_shop_name('Etsy')

            if 'listing_id' in item:
                product.set_product_id(item['listing_id'])

            if 'title' in item:
                product.set_product_name(item['title'])

            if 'description' in item:
                product.set_product_descr(item['description'])

            if 'url' in item:
                product.set_product_shop_url(item['url'])

            if 'price' in item:
                product.set_product_price(item['price'])

            if 'currency_code' in item:
                product.set_price_curr(item['currency_code'])

            if 'category_path' in item:
                product.set_product_category(item['category_path'][0])

            if 'ending_tsz' in item:
                product.set_valid_until(item['ending_tsz'])

            if 'Images' in item:
                try:
                    product.set_product_img(item['Images'][0]['url_fullxfull'])
                except:
                    product.set_product_img("_")

            product.set_product_date_added_db(time.mktime(datetime.now().timetuple()))

            product_obj_list.append(product)
        
        push_object_data_to_db(product_obj_list)
        product_obj_list.clear()
    else:
        print(req)

if __name__ == "__main__":
    ######################### SCRIPT START #########################
    # A Pyhton 3 script that fetches data from Etsy's RESTful API  #
    # and adds new entries to product DB or updates them if they   #
    # already exist. Also pushes the rows of data to a replicated  #
    # DB, used in other parts of the app to track item visit data. #
    ################################################################
    APP_KEY = ''
    URL = 'https://openapi.etsy.com/v2/listings/active?api_key={}&includes=Images:1:0&limit=200'.format(APP_KEY)
    HEADERS = {'X-RateLimit-Limit' : '10000', 'X-RateLimit-Remaining' : '9924'}

    start(URL, HEADERS)
