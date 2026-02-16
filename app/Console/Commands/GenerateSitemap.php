<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

use App\Models\Product;
use App\Models\Article;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml';

    public function handle()
    {
        $domain = 'https://talaaljazeera.com';

        $sitemap = Sitemap::create()
            // الصفحة الرئيسية
            ->add(Url::create($domain . '/')->setPriority(1.0))
            // صفحات ثابتة
            ->add(Url::create($domain . '/about'))
            ->add(Url::create($domain . '/contact'));

        // المنتجات
        $products = Product::all();
        foreach ($products as $product) {
            $sitemap->add(Url::create($domain . "/product/{$product->sku}")
                ->setLastModificationDate($product->updated_at));
        }

        // المقالات / بلوج
        $articles = Article::all();
        foreach ($articles as $article) {
            $sitemap->add(Url::create($domain . "/blogs/{$article->slug}")
                ->setLastModificationDate($article->updated_at));
        }

        // احفظ الملف
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully for ' . $domain);
    }
}
