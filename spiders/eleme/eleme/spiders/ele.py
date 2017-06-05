# coding:utf-8

import scrapy
from scrapy.http import Request
from eleme.items import ElemeItem
import urllib
import json
import Geohash


class Myspider(scrapy.Spider):
    name = 'eleme'
    allowed_domains = ['ele.me']
    bash_url = 'https://www.ele.me'

    restaurant_url = 'https://mainsite-restapi.ele.me/shopping/restaurants'
    restaurant_query = {
        'extras[]': 'activities',
        'restaurant_category_ids[]': '207',
        'limit': 20,
        'offset': 0,
        # 'terminal': 'web',
    }

    food_url = 'https://mainsite-restapi.ele.me/shopping/v2/menu?restaurant_id='

    def __init__(self):
        with open('geohash.json', 'r') as f:
            self.geohash = json.load(f).values()

    def start_requests(self):
        for i in self.geohash:
            (self.restaurant_query['latitude'], self.restaurant_query['longitude']) = Geohash.decode(i)
            url = self.restaurant_url + '?' + urllib.urlencode(self.restaurant_query)
            yield Request(url=url, callback=self.parse, meta={'geohash': i})

    def parse(self, response):
        jsonresponse = json.loads(response.body_as_unicode())
        # with open('logs/%s-%d' % (response.meta['geohash'], self.restaurant_query['offset']), 'wb') as f:
        #     f.write(response.body_as_unicode().encode('utf-8'))
        for i in jsonresponse:
            i['geohash'] = response.meta['geohash']
            yield self.get_item(i)
            # url = self.food_url + str(i['id'])
            # yield Request(url=url, callback=self.parse_food)
        if len(jsonresponse) == self.restaurant_query['limit']:
            self.restaurant_query['offset'] += self.restaurant_query['limit']
            url = self.restaurant_url + '?' + urllib.urlencode(self.restaurant_query)
            yield Request(url=url, callback=self.parse, meta={'geohash': response.meta['geohash']})

    def get_item(self, restaurant):
        item = ElemeItem()
        item['name'] = restaurant['name']
        item['id'] = restaurant['id']
        item['recent_order_num'] = restaurant['recent_order_num']
        item['average_cost'] = restaurant.get('average_cost', '')
        item['address'] = restaurant['address']
        item['geohash'] = restaurant['geohash']
        item['distance'] = restaurant['distance']
        return item

    def parse_food(self, response):
        jsonresponse = json.loads(response.body_as_unicode())
        for i in jsonresponse:
            yield self.get_food(i)

    def get_food(self, food):
        print food['name']
        # item = ElemeItem()
        # item['name'] = food['name']
        # item['id'] = food['id']
        # item['recent_order_num'] = food['recent_order_num']
        # item['average_cost'] = food.get('average_cost', '')
        # item['address'] = food['address']
        # item['distance'] = food['distance']
        # return item
