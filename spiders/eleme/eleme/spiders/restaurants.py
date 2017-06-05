# coding:utf-8
import scrapy
import json
import urllib
import time
import Geohash
from eleme.items import RestaurantItem


class RestaurantsSpider(scrapy.Spider):
    name = "restaurants"
    date = time.strftime("%Y%m%d", time.localtime())

    places_url = 'http://dockerhost/api/map/place'

    restaurant_url = 'https://mainsite-restapi.ele.me/shopping/restaurants'
    restaurant_query_default = {
        'extras[]': ['activities'],
        'limit': 20,
        'offset': -20,
        # 'terminal': 'h5',  # web, h5
        # 'restaurant_category_ids[]': [209, 211, 212, 213, 214, 215, 216, 217, 218, 219, 221, 222, 223, 224, 225, 226,227, 228, 229, 230, 231, 232, 234, 235, 236, 237, 238, 263, 264, 265, 266, 267,268, 269],
    }
    restaurant_query_extend = {
    }

    def start_requests(self):
        yield scrapy.Request(url=self.places_url, callback=self.parse_places)

    def parse_places(self, response):
        places = json.loads(response.body_as_unicode())
        places = places[0:2]
        for place in places:
            (self.restaurant_query_default['longitude'], self.restaurant_query_default['latitude']) = map(
                lambda str: str.strip(), place['location'].split(','))
            yield self.page(place)

    def page(self, data):
        self.restaurant_query_default['offset'] += self.restaurant_query_default['limit']
        url = self.restaurant_url + '?' + urllib.urlencode(self.restaurant_query_default, True)
        return scrapy.Request(url=url, callback=self.parse, meta={
            'location': '哈尔滨市',
            'name': data['name'],
            'date': self.date,
        })

    def parse(self, response):
        jsonresponse = json.loads(response.body_as_unicode())
        for restaurant in jsonresponse[0:3]:
            yield self.getItem(response.meta, restaurant)
        if len(jsonresponse) == self.restaurant_query_default['limit']:
            # yield self.page(response.meta)
            pass

    def getItem(self, meta, restaurant):
        item = RestaurantItem()
        item['location'] = meta['location']
        item['place'] = meta['name']
        item['date'] = meta['date']
        item['restaurant'] = restaurant
        return item
