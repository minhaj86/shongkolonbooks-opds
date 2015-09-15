---opencart to opds converting queries
SELECT * FROM oc_product as prod , oc_product_description as des  WHERE prod.product_id=des.product_id AND prod.format='ebook' AND des.language_id=1

