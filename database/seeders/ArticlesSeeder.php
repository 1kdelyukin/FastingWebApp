<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticlesSeeder extends Seeder
{
    public function run(): void
    {
        Article::updateOrCreate(
            ['article_title' => 'Tips for Intermittent Fasting'],
            [
                'article_info' => "1 / Stay Busy\nKeeping yourself occupied can help distract from hunger pangs during fasting periods\n\n2 / Expect Challenges\nThe first week of intermittent fasting can be challenging as your body adjusts to the new eating pattern\n\n3 / Hydration Is Key\nDrink plenty of water throughout the day to stay hydrated and help curb hunger\n\n4 / Plan Your Day\nPlan your activities and tasks during fasting hours to keep your mind off food and stay productive.\n\n5 / Start Light\nBegin your eating window with foods that are easy to digest, such as smoothies or vegetarian options.\n\n6 / It's Not Starvation\nRemember that intermittent fasting is not starvation; it is about giving your body more time to rest and recover from digestion\n\n7 / Exercise for Energy\nSome people may experience increased energy levels when exercising towards the end of a fasting period. Listen to your body and adjust your exercise routine",
                'article_thumbnail' => 'images/fasting-tips.png',
                'article_tag' => 'Intermittent Fasting'
            ]
        );
        
        Article::updateOrCreate(
            ['article_title' => 'What is Intermittent Fasting and its Benefits?'],
            [
                'article_info' => "An introduction to a transformative dietary approach.\n\nIntermittent fasting involves alternating periods of eating and fasting, offering a structured yet flexible strategy with potential health benefits for both body and mind.\n\nThere are various approaches to intermittent fasting, one of which is the 16:8 approach.",
                'article_thumbnail' => 'images/intermittent_fasting_benefits.png',
                'article_tag' => 'Intermittent Fasting'
            ]
        );

        Article::updateOrCreate(
            ['article_title' => 'What is an Anti-inflammatory Diet?'],
            [
                'article_info' => "By choosing foods that fight inflammation, you can potentially improve your overall health and reduce your risk of developing inflammatory conditions.\n\n Consuming anti-inflammatory foods and drinks while avoiding pro-inflammatory products may help you reduce the risk of inflammation related illnesses.",
                'article_thumbnail' => 'images/anti_inflammatory_diet.png',
                'article_tag' => 'Anti-inflammatory'
            ]
        );
    }
}
