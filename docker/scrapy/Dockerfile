FROM python:2.7

COPY ./pip.conf /root/.pip/pip.conf

RUN pip install Scrapy

RUN pip install MySQL-python

RUN pip install Geohash

RUN pip install pymongo

RUN pip install sqlalchemy

RUN cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime

WORKDIR /var/www
