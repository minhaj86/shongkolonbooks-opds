#!/bin/sh
mysqldump -u root -p123qwe  opencart opds_authors opds_books opds_book_to_author opds_catagory opds_catalogs opds_publishers opds_users opds_storages opds_files opds_user_to_file > data/db.sql
