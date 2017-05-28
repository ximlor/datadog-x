from .sql import Sql
from eleme.items import ElemeItem


class ElemePipeline(object):
    def process_item(self, item, spider):
        if isinstance(item, ElemeItem):
            name = item['name']
            id = item['id']
            recent_order_num = item['recent_order_num']
            average_cost = item['average_cost']
            address = item['address']
            geohash = item['geohash']
            distance = item['distance']
            if Sql.exist(id)[0] == 1:
                Sql.update(id, recent_order_num, average_cost)
                pass
            else:
                Sql.insert(name, id, recent_order_num, average_cost, address, geohash, distance)
