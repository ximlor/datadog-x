# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class ElemeItem(scrapy.Item):
    # define the fields for your item here like:
    # name = scrapy.Field()
    name = scrapy.Field()
    id = scrapy.Field()
    recent_order_num = scrapy.Field()
    average_cost = scrapy.Field()
    address = scrapy.Field()
    geohash = scrapy.Field()
    distance = scrapy.Field()
    pass


class FoodItem(scrapy.Item):
    # define the fields for your item here like:
    # name = scrapy.Field()
    name = scrapy.Field()
    restaurant_id = scrapy.Field()
    item_id = scrapy.Field()
    food_id = scrapy.Field()
    price = scrapy.Field()
    recent_popularity = scrapy.Field()
    pass
