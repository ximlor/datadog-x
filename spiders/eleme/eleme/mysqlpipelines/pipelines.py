# coding:utf-8
import MySQLdb
from eleme import settings
from sqlalchemy import Column, String, Integer, create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base
from eleme.items import RestaurantItem

# 创建对象的基类
Base = declarative_base()


class Restaurant(Base):
    __tablename__ = 'eleme_restaurants'

    id = Column(Integer, primary_key=True)
    name = Column(String(521))
    recent_order_num = Column(Integer)
    average_cost = Column(String(521))
    address = Column(String(1024))
    distance = Column(Integer)


class ElemePipeline(object):
    def open_spider(self, spider):
        engine = create_engine(
            'mysql+mysqldb://%s:%s@%s:3306/%s?charset=utf8' % (
                settings.MYSQL_USER, settings.MYSQL_PASSWORD, settings.MYSQL_HOST,
                settings.MYSQL_DB))
        self.sessionDB = sessionmaker(bind=engine)
        self.session = self.sessionDB()

    def close_spider(self, spider):
        try:
            self.session.commit()
        except:
            self.session.rollback()
            raise
        finally:
            self.session.close()

    def process_item(self, item, spider):
        if isinstance(item, RestaurantItem):
            r = {
                'id': item['restaurant']['id'],
                'name': item['restaurant']['name'],
                'recent_order_num': item['restaurant']['recent_order_num'],
                'average_cost': item['restaurant']['average_cost'],
                'address': item['restaurant']['address'],
                'distance': item['restaurant']['distance'],
            }
            new_r = Restaurant(**r)
            self.session.merge(new_r)
        return item
