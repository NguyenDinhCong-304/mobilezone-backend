<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```
nguyendinhcong-app
├─ .editorconfig
├─ app
│  ├─ Exports
│  │  └─ ProductSaleTemplateExport.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ AttributeController.php
│  │  │  ├─ AuthController.php
│  │  │  ├─ BannerController.php
│  │  │  ├─ BrandController.php
│  │  │  ├─ CategoryController.php
│  │  │  ├─ ContactController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ DashboardController.php
│  │  │  ├─ MenuController.php
│  │  │  ├─ OrderController.php
│  │  │  ├─ PostController.php
│  │  │  ├─ ProductController.php
│  │  │  ├─ ProductSaleController.php
│  │  │  ├─ ProductStoreController.php
│  │  │  ├─ SettingController.php
│  │  │  ├─ TopicController.php
│  │  │  ├─ UploadController.php
│  │  │  └─ UserController.php
│  │  └─ Middleware
│  │     └─ AdminMiddleware.php
│  ├─ Imports
│  │  └─ ProductSaleImport.php
│  ├─ Mail
│  │  ├─ OrderConfirmMail.php
│  │  └─ VerifyEmailMail.php
│  ├─ Models
│  │  ├─ Attribute.php
│  │  ├─ Banner.php
│  │  ├─ Brand.php
│  │  ├─ Category.php
│  │  ├─ Contact.php
│  │  ├─ Menu.php
│  │  ├─ Order.php
│  │  ├─ OrderDetail.php
│  │  ├─ Post.php
│  │  ├─ Product.php
│  │  ├─ ProductAttribute.php
│  │  ├─ ProductImage.php
│  │  ├─ ProductSale.php
│  │  ├─ ProductStore.php
│  │  ├─ Setting.php
│  │  ├─ Topic.php
│  │  └─ User.php
│  └─ Providers
│     └─ AppServiceProvider.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ cors.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ jwt.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ sanctum.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 2025_09_09_105730_create_personal_access_tokens_table.php
│  │  ├─ 2025_09_09_122237_create_banner_table.php
│  │  ├─ 2025_09_09_122914_create_category_table.php
│  │  ├─ 2025_09_09_123148_create_contact_table.php
│  │  ├─ 2025_09_09_123255_create_product_table.php
│  │  ├─ 2025_09_09_123342_create_product_image_table.php
│  │  ├─ 2025_09_09_123428_create_product_sale_table.php
│  │  ├─ 2025_09_09_123436_create_product_store_table.php
│  │  ├─ 2025_09_09_123443_create_attribute_table.php
│  │  ├─ 2025_09_09_123449_create_product_attribute_table.php
│  │  ├─ 2025_09_09_124021_create_menu_table.php
│  │  ├─ 2025_09_09_124222_create_topic_table.php
│  │  ├─ 2025_09_09_124230_create_post_table.php
│  │  ├─ 2025_09_09_124239_create_user_table.php
│  │  ├─ 2025_09_09_124250_create_order_table.php
│  │  ├─ 2025_09_09_124258_create_orderdetail_table.php
│  │  ├─ 2025_09_09_124303_create_setting_table.php
│  │  ├─ 2025_10_17_052422_add_email_verify_columns_to_ndc_user_table.php
│  │  ├─ 2025_12_08_091802_create_brands_table.php
│  │  ├─ 2025_12_08_092847_add_brand_id_to_product.php
│  │  ├─ 2025_12_08_114113_create_brand_table.php
│  │  ├─ 2025_12_08_115035_add_more_fields_to_ndc_brands.php
│  │  ├─ 2025_12_17_095552_change_description_column_table_product.php
│  │  ├─ 2026_01_12_183251_add_fk_product_brand.php
│  │  ├─ 2026_01_12_184827_add_fk_orders_user.php
│  │  ├─ 2026_01_12_185256_add_fk_orderdetail.php
│  │  ├─ 2026_01_12_190716_fix_fk_contact_user.php
│  │  ├─ 2026_01_12_190753_fix_fk_contact_user.php
│  │  └─ 2026_01_12_191344_add_fk_menu_parent.php
│  └─ seeders
│     ├─ AttributeSeeder.php
│     ├─ BannerSeeder.php
│     ├─ BrandSeeder.php
│     ├─ CategorySeeder.php
│     ├─ ContactSeeder.php
│     ├─ DatabaseSeeder.php
│     ├─ MenuSeeder.php
│     ├─ OrderdetailSeeder.php
│     ├─ OrderSeeder.php
│     ├─ PostSeeder.php
│     ├─ ProductAttributeSeeder.php
│     ├─ ProductImageSeeder.php
│     ├─ ProductSaleSeeder.php
│     ├─ ProductSeeder.php
│     ├─ ProductStoreSeeder.php
│     ├─ SettingSeeder.php
│     ├─ TopicSeeder.php
│     └─ UserSeeder.php
├─ package.json
├─ phpunit.xml
├─ public
│  ├─ .htaccess
│  ├─ favicon.ico
│  ├─ index.php
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ emails
│     │  ├─ order-confirm.blade.php
│     │  └─ verify-email.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ api.php
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  ├─ private
│  │  └─ public
│  │     ├─ banners
│  │     │  ├─ 1fnt9DkKPfpi4lDo4kZ47vdTZibxWez5I7UVD1Df.jpg
│  │     │  ├─ QA47277LYyc0Y6Hc5lEhNGw0HZ9fR8oC4MF6rprM.jpg
│  │     │  └─ vsPWAxbVO5KluV6W22ef1GZqmMaZTLgJnFH7NbVU.jpg
│  │     ├─ brands
│  │     ├─ category
│  │     │  ├─ DD1clpRoqEJqhy33FoGXIUgZjVQNm6WcXSX7iZe2.webp
│  │     │  ├─ JQt02XTOVUnLYXhGeL88dANdPRSVPvNMEeeuajeI.webp
│  │     │  ├─ jSjDqCxlzOlibbA5yRsZ4J9mgwngKCDWQJqHFLhf.webp
│  │     │  ├─ lBklrqwfB10ZAfYxBDIZCtZXqXDwnNPxBxABFC4l.jpg
│  │     │  ├─ mPqeJVT7F3K6as1CQJl3jU9WDaX32KTqZxdCei6k.webp
│  │     │  └─ U8usihlR0cvBBFHZa2AiRlJDMiMPrsApYdUPH9pD.jpg
│  │     ├─ products
│  │     │  ├─ images
│  │     │  │  ├─ 07if0omr7ejzsQHnNbNXQSn545mca9WekTjxa67W.webp
│  │     │  │  ├─ 0B9T9CgQ8byzDXn2UsnuQcjGVgLDUR0bl4tPzcPU.webp
│  │     │  │  ├─ 0ENZwyibLhbJBQTHD0Ve7h0RZ5XyVH7BvT7wOea7.webp
│  │     │  │  ├─ 4i8sqnrnZngKIq4RyYzFPpvZDTjMmqxvHdjlpxbQ.webp
│  │     │  │  ├─ 5XdYQENjKL1MfL79TNTWhsnxiGHyqCnl5C2FqN6B.webp
│  │     │  │  ├─ 6TxpsXJ0JVrKTW8KO5mqHvaNCRs9NP54C0QSjSWh.webp
│  │     │  │  ├─ 7LgEcnEMiUV18r3FbzKLgD270QndNy4GND2albL8.webp
│  │     │  │  ├─ 7sgoPGKdpaaCMDtiHpjOy40wQNLgrwI8R2S37BrS.jpg
│  │     │  │  ├─ 7X4vy5hbpRoj8tOkZetVfkGWkmRXtSI9xhQXw41Y.jpg
│  │     │  │  ├─ 8aa9WZSIKQF17TvEvyNXgBbkpqUGz29dQrv4ktCO.webp
│  │     │  │  ├─ 8BSMkQDmPCB0ETDma35U6oRZLagl6vezHJpheXfG.jpg
│  │     │  │  ├─ aBnD9Zsr1yQC2kX4D4irGf3TQe1ikNpWXb4smTh9.webp
│  │     │  │  ├─ AxhYPxvgHczmOL6rBtKx6R9TAEhVCR1h6KPs3TrQ.webp
│  │     │  │  ├─ cU40SPkrQVXcPqIW9kfnVWFzud4r4cl1E8d1CiuG.webp
│  │     │  │  ├─ dPDvWzGXQzjT4LMs0UHNnT9RISGAaEDupd7UW996.webp
│  │     │  │  ├─ dtkPDwhyQE1QPeK6EYlNlrPlMqJvPgcTngsURi67.webp
│  │     │  │  ├─ EQ7cySrSC1Dt9lWI717ek1f1TWOfAhAKWbQuP0wC.webp
│  │     │  │  ├─ fBy7vu5Y2ywobILENBPd1g6VXdUbHhcMNTvZsns8.webp
│  │     │  │  ├─ FDHGXE6Fpy7QfOtEMyp6dnFCKUx47eDUVXqJcdAz.webp
│  │     │  │  ├─ FgfrNbQYsUf3XqN3WEpODJI4u18unGqFkk0oPH3W.webp
│  │     │  │  ├─ GPBomgb5mcVZP4b9fJFR8oT2z9YiFZcYtAr6zxa0.jpg
│  │     │  │  ├─ HLS88Y9drmWAn6ij2CilaMPkCA1LNHVlI3NKFvN1.webp
│  │     │  │  ├─ I5R1kcyc030pJp2zqHiT13tQNjfKozmQ0ru7ncPP.jpg
│  │     │  │  ├─ irRDseC4cSVzCxpY2DUX1k1GZqQqQ5EdDCUjblGG.jpg
│  │     │  │  ├─ Iy2MiV35cH6Hbnt7cv8GAJ9Euuqjt9s1UYbsSMCO.webp
│  │     │  │  ├─ jtQPR9EYSa4sKwyBBlPuTKeyijL9PWIkPV83Ypp1.webp
│  │     │  │  ├─ JZ0DitafQe74a5yBYrdiZmeQFGUfScHwEnbdncB6.webp
│  │     │  │  ├─ kBqPOF02wJZBxABEpOseAllZeRB40wsd0Ek9EFzT.jpg
│  │     │  │  ├─ l3wlD3rICBWtnHIjgBoLun6AyyRnbJiQ5t5uzYmr.webp
│  │     │  │  ├─ LjU1pMFItSAssmnKeJqxhQjOpsMPgu4PXsrhgdyu.webp
│  │     │  │  ├─ MJpdCDheqwi15p54icNmC1mST3VXhkeNbzf0s3mh.jpg
│  │     │  │  ├─ muHdkjzygYXuQiV8v4xRUiv4bNIvJYoBC4cabs7b.webp
│  │     │  │  ├─ mwmlkkYAFAI4SDZ4i1Yjj7xjVrGceQZvSbFfwgYv.jpg
│  │     │  │  ├─ NB1liPNmYZCUjDetIzlw80VPvSVbDi0TXkFUMvnR.webp
│  │     │  │  ├─ O8EwLAfz59btEEPop2WMkt1apcK4nK56bMEIpivq.webp
│  │     │  │  ├─ omoGR7TZmFqTVTWQoWfP3WtxR6K3Rd8JQjtD1abf.webp
│  │     │  │  ├─ Qer5ZgFrD0xP7QrBNeh3e9tLQ9e8zAXfoNGs4LkA.jpg
│  │     │  │  ├─ qxJTjUJU6gZhnk2ScKIUG1GVps2oQLcSkQ75x7EJ.jpg
│  │     │  │  ├─ r30yjMogKdctSF81rm8QtE6D4BZM8KYnSAkW1gR1.webp
│  │     │  │  ├─ tlC3T2JuAtipqIPSERS9U3uSulUEwczSzjEVVl2a.jpg
│  │     │  │  ├─ U5gLppbXHZvLYzMptbnQQDpcmNCzUnVhrvy45I6M.jpg
│  │     │  │  ├─ uXITlOast8C7qd0uQDYlJaHSdtZeMaKoVZ2kSDjs.webp
│  │     │  │  ├─ V9QLkP9FvNxSjnXJTLZTYCwaZ7EwKGW640YwMPPS.webp
│  │     │  │  ├─ vbrtAZOQHCPwnUrfomf5NXFJ0AEpKqLqrP2QExD4.webp
│  │     │  │  ├─ vDpLwAh9c3TN8SjbPQLtFBChxvJ2UI9cJYVKrukL.jpg
│  │     │  │  ├─ vg25D87vAzYqfeay90ggAfQbEjfZX3ZpdiwDOHij.jpg
│  │     │  │  ├─ VsmHco3cVi2sG2HHNcRm2mmMrAmf9YCdEQXHPniM.webp
│  │     │  │  ├─ vXUXicDzmQCmhi9hU1TZWBljKc2QDnb7Rs8v8dAI.jpg
│  │     │  │  ├─ wLLeigr0IirJjklglGfHdQLXbcCMhwYtR33KL3lR.webp
│  │     │  │  ├─ WMKuCRUnKSZRqzF5epgq7yhEW4dmBQirH2WkBAE6.webp
│  │     │  │  ├─ xHie292NLKEdHRmWyURpjxheJ9nwTcs9gYG7R7cZ.webp
│  │     │  │  ├─ xK473br4OuIjEwN9CXCljlYYxU5XwUa2IReTj9kC.jpg
│  │     │  │  ├─ xQMNwqcM6lhr7Wdc270HqD5WZuLmtuwhgGXH28Gm.webp
│  │     │  │  ├─ yiFXNmHraw8hsShBGgg3kkpB3o9OlTq6O3kabwnn.webp
│  │     │  │  ├─ yjrkUb64GAZhKxodYus56f4chER80f6VNHDeBr1M.webp
│  │     │  │  ├─ z8KLnokhfhOhZuUTGnqNvxLSFCyGErLigW4qx4Kg.webp
│  │     │  │  ├─ Zk8PqhSp7HE1qKKLBdkwR4BdQsepD5ZZiPDtIlda.webp
│  │     │  │  └─ ZzfgJ9mNF3NQyC6PPFnCvZP2JoRL6ncJEa3mcSBM.webp
│  │     │  └─ thumbnails
│  │     │     ├─ 09gxUdfX5g8P0O7oqJYdUgAaWuZh9sfjn2JdBaXS.webp
│  │     │     ├─ 0B9T9CgQ8byzDXn2UsnuQcjGVgLDUR0bl4tPzcPU.webp
│  │     │     ├─ 5XdYQENjKL1MfL79TNTWhsnxiGHyqCnl5C2FqN6B.webp
│  │     │     ├─ 6jUEEhiif70Q7Ug9wLaafOZrqqObgkXwRKS8V7Dj.jpg
│  │     │     ├─ 7LgEcnEMiUV18r3FbzKLgD270QndNy4GND2albL8.webp
│  │     │     ├─ 8chL0hPwoaECCfp5wFgKT1OvBzTlBTp4crnBiWD1.jpg
│  │     │     ├─ AxhYPxvgHczmOL6rBtKx6R9TAEhVCR1h6KPs3TrQ.webp
│  │     │     ├─ dtkPDwhyQE1QPeK6EYlNlrPlMqJvPgcTngsURi67.webp
│  │     │     ├─ EQ7cySrSC1Dt9lWI717ek1f1TWOfAhAKWbQuP0wC.webp
│  │     │     ├─ FgfrNbQYsUf3XqN3WEpODJI4u18unGqFkk0oPH3W.webp
│  │     │     ├─ GAzDOW6yjAlCpEqD8Jy34dll7q8QKX7AQXhuSk2C.webp
│  │     │     ├─ GPBomgb5mcVZP4b9fJFR8oT2z9YiFZcYtAr6zxa0.jpg
│  │     │     ├─ gPOgXeBGdBkokL3RlNm6UEhJkAVgqxAcTnCgm0op.webp
│  │     │     ├─ H9RTsJawRRCIKTXG8Ekw1Z2bIaNMuYaDTRHah0w4.webp
│  │     │     ├─ HL1Shv6dgQovHHL63YrihEUxoMSRsMItMhWDBpBz.webp
│  │     │     ├─ IaORWURixuvviNEbJ4Vk9dSp0iXnVJDoQ1y44hyh.jpg
│  │     │     ├─ ibkWV6iPPVvN225iwvXgv2JW41W6Nj1iMXjCORlO.webp
│  │     │     ├─ Iy2MiV35cH6Hbnt7cv8GAJ9Euuqjt9s1UYbsSMCO.webp
│  │     │     ├─ izv6eIO11bLFJtZWFI6CuDnQHN00Zrx4FXNoXEzt.jpg
│  │     │     ├─ jtQPR9EYSa4sKwyBBlPuTKeyijL9PWIkPV83Ypp1.webp
│  │     │     ├─ JZ0DitafQe74a5yBYrdiZmeQFGUfScHwEnbdncB6.webp
│  │     │     ├─ kBqPOF02wJZBxABEpOseAllZeRB40wsd0Ek9EFzT.jpg
│  │     │     ├─ l3wlD3rICBWtnHIjgBoLun6AyyRnbJiQ5t5uzYmr.webp
│  │     │     ├─ LvCAWHeDK0pxsmNPLXf6mJaVuChU9oaV4YnFX10u.jpg
│  │     │     ├─ M7FSx3jiRNRgeoBlm1L6Emp7yFWJVwbdRlu6jsdv.jpg
│  │     │     ├─ MDaBAcJYG4vt7k0F6wm2xEC8UqL4zOxiGFbrxvzZ.webp
│  │     │     ├─ MJpdCDheqwi15p54icNmC1mST3VXhkeNbzf0s3mh.jpg
│  │     │     ├─ nEoW3y84OurQSlUBxfU6sVUWzhrL7wxe6zkccKMY.webp
│  │     │     ├─ omoGR7TZmFqTVTWQoWfP3WtxR6K3Rd8JQjtD1abf.webp
│  │     │     ├─ OT9jY20lTDB7BUeCIZfZmwoSDLQ4L6HIePqYJTOC.jpg
│  │     │     ├─ Pgqr1jc1WleiZQ99khusBOwT2NrJGWSLEsclNGF6.jpg
│  │     │     ├─ Q8ctqVpNGMmRT9VKHTLsDH6zh3X6mJG7eqy4VH2f.webp
│  │     │     ├─ r30yjMogKdctSF81rm8QtE6D4BZM8KYnSAkW1gR1.webp
│  │     │     ├─ tHA7ezSbEm6FN57yGU0MM8GqUoitn8E3OFydVZOM.jpg
│  │     │     ├─ tlC3T2JuAtipqIPSERS9U3uSulUEwczSzjEVVl2a.jpg
│  │     │     ├─ ujcquzjUnpr8wSo2PwzVg1cMRi7QJZJehPgtjD6M.jpg
│  │     │     ├─ x43np9noXb0fbdztMi1S6Mi6YCBZqHDiVQY7v5wZ.webp
│  │     │     ├─ xK473br4OuIjEwN9CXCljlYYxU5XwUa2IReTj9kC.jpg
│  │     │     ├─ yiFXNmHraw8hsShBGgg3kkpB3o9OlTq6O3kabwnn.webp
│  │     │     └─ Zk8PqhSp7HE1qKKLBdkwR4BdQsepD5ZZiPDtIlda.webp
│  │     ├─ summernote
│  │     │  ├─ 8FQXJUyyD4eWYPnjNXMJITopR6UVvgIUCX03qIVS.jpg
│  │     │  ├─ iJgeUpG041MqFJyf0K5HL9tHz03CfeCzMFRRuZ0T.jpg
│  │     │  └─ JLmlwkDKwJXhklcYYrucUoMEAmziYMNji5pdhVXB.jpg
│  │     ├─ uploads
│  │     │  └─ posts
│  │     │     ├─ 1NOjpnDZgXnhtLyWHomm2Oq2IjFCRqXX93UpynaB.webp
│  │     │     ├─ 6mUyqOXm9JQWi1JCqbM5EK5YTLi5EGSMidOFQPBr.webp
│  │     │     ├─ kQOEhm96KnTn8AuXwaSa2xLX8ZqeGNzzdGoQsuhO.webp
│  │     │     ├─ nsZydIu83xxtjqcgLiPHu4C4gMr9JB4mPNqCn0AO.webp
│  │     │     └─ R50iSQ8fakOxmTqjHEVydUN32HBxwKS72c07NxEk.jpg
│  │     └─ user
│  │        ├─ ofcrmGIBENp9uCn3dxPGaHcAkNA2scdLufuLNqL7.jpg
│  │        ├─ qKdexN4sY97E8t1c3E2dDcNt2dRDNh7QPic1qGvL.jpg
│  │        ├─ Tjx9y8yrju6fYnYl3FzTMaJ0pCKP3GmYtsejkJbZ.jpg
│  │        └─ Yup3Hh6CHP31p3qlrZqen4m6mlQvS52JpWrqH4HK.jpg
│  ├─ framework
│  │  ├─ cache
│  │  │  ├─ data
│  │  │  └─ laravel-excel
│  │  ├─ sessions
│  │  ├─ testing
│  │  └─ views
│  │     ├─ 1f3084834a1a75ad388749e597fb3ca8.php
│  │     ├─ 245201843a0626766c0c73284eb3be57.php
│  │     ├─ 33f5a36e12702611b63910c1f8a59b26.php
│  │     ├─ 45e7c8c777dba94b212c14d598d1b3db.php
│  │     ├─ 4a77d0dca9c9138b482dd5f70420aa7e.php
│  │     ├─ 4c36117d45e089b468d5370c94448179.php
│  │     ├─ 690830c7fa1ec96196e79c394b2da3b0.php
│  │     ├─ 69643aada9f442a115257acc89f87d20.php
│  │     ├─ 69cda7c0097f2a4b9f2bd2934509a949.php
│  │     ├─ 6ee57f25fdb2bce4aa724e4654cc1dbd.php
│  │     ├─ 7f96fd5536dee73e940e7a794330692e.php
│  │     ├─ 9796e03997ec55737f4360df7b96016b.php
│  │     ├─ a1b4076afb76461e1f4213b663c6a77f.php
│  │     ├─ a1c85d879b1dffc84dc3b57cf96bfa92.php
│  │     ├─ a3270989568dc3c2f36275f43cb593aa.php
│  │     ├─ b159e40a9a2bab4e3ba86a979bb09061.php
│  │     ├─ bef1a764a1d0504a41cc03bd66b6eb0b.php
│  │     ├─ c50bbfe0e86f08b910ae87b01ad88089.php
│  │     ├─ c61dbe50988f05a97e20ca72ef73d963.php
│  │     ├─ e832b9513f32854f4d8d2f51c7623d2c.php
│  │     ├─ ed09d08cf284f7949dca5a3206fbc42c.php
│  │     └─ f27b8c7409697d4e2f8393fc2f0d0b33.php
│  └─ logs
├─ tests
│  ├─ Feature
│  │  └─ ExampleTest.php
│  ├─ Pest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```