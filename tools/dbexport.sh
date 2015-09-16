#!/bin/sh
mysqldump -u root -p123qwe  opencart opds_authors opds_books opds_book_to_author opds_catagory opds_catalogs opds_publishers opds_users > data/db.sql
