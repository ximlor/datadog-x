import MySQLdb
from eleme import settings

db = MySQLdb.connect(host=settings.MYSQL_HOST, user=settings.MYSQL_USER, passwd=settings.MYSQL_PASSWORD,
                     db=settings.MYSQL_DB, charset='utf8')
cursor = db.cursor()


class Sql:
    @classmethod
    def insert(cls, name, id, recent_order_num, average_cost, address, geohash, distance):
        print 'insert'
        sql = "INSERT INTO eleme (id, name, recent_order_num, average_cost, address, geohash, distance) VALUES ('%s','%s', '%s', '%s', '%s', '%s', '%s' )"
        try:
            cursor.execute(sql % (id, name, recent_order_num, average_cost, address, geohash, distance))
            db.commit()
        except:
            db.rollback()
            print 'insert error'

    @classmethod
    def exist(cls, id):
        sql = "SELECT EXISTS(SELECT 1 FROM eleme WHERE id='%s')"
        try:
            cursor.execute(sql % (id))
            results = cursor.fetchall()
            return results[0];
        except:
            print 'exist error'

    @classmethod
    def update(cls, id, recent_order_num, average_cost):
        print 'update'
        sql = "UPDATE eleme SET recent_order_num='%s', average_cost='%s' WHERE id='%s'"
        try:
            cursor.execute(sql % (recent_order_num, average_cost, id))
            db.commit()
        except:
            db.rollback()
            print 'update error'
