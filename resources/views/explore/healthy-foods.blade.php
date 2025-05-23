@extends('layouts.explore')

@section('content')

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<x-header />

<div class="w-full overflow-hidden">    <img src="../images/healthyfoodsArticle.png" 
         class="w-full md:h-64 lg:h-80 object-cover object-center"
         alt="Healthy Foods Header Image"/>
</div>

<div class="flex flex-col items-center justify-center pb-20 p-2">

<div class="w-full max-w-4xl mx-auto p-4 space-y-6">
        <!-- Article Details -->
        <div class="bg-white rounded-lg shadow-md p-4">
            <ol class = "list-decimal pl-6">
                <li>Omega-3</li>
                <ul class="list-disc pl-6">
                    <li><strong>Fatty fish </strong>- serialized fish, mackerel, tuna</li>
                    <li><strong>Certain nuts & seeds </strong> - flaxseeds, chia seeds, walnuts</li>
                    <li><strong>Certain plant oils </strong> - flaxseed oil, soybean oil, canola oil</li>
                </ul>
                <li>Antioxidants</li>
                <ul class="list-disc pl-6">
                    <li><strong>Vitamin C</strong> - citrus fruits, broccoli, brussel sprouts</li>
                    <li><strong>Vitamin E </strong> - almonds, asparagus, avocados, spinach</li>
                    <li><strong>β-carotene </strong> - carrots, spinach, sweet potatoes, broccoli</li>
                    <li><strong>Polyphenols</strong> - berries, herbs & spices, broccoli, carrots, spinach, onions, asparagus</li>
                </ul>

                <li>Promotors of gut health</li>
                <ul class = "list-disc pl-6">
                    <li><strong>Prebiotics</strong> - whole grain breads & pastas, beans, lentils, peas</li>
                    <li><strong>Probiotics</strong> - Greek yogurt, kimchi, sauerkraut, miso, tempeh</li>
                </ul>
            </ol>
            <div class="flex flex-col gap-3">
                <h2><span class="text-lg font-semibold">Summary</span> </h2>
                <div class = "w-full fkex flex-col">
                    <img src = "../images/explore_anti_inflammatory.png" class = "w-full rounded-xl"/>
                    <img src = "../images/list_hf.png" class = "w-full py-3 rounded-2xl"/>
                </div>     
                
                <h2><span class="text-lg font-semibold">Foods to Avoid</span> </h2>
                <div class = "w-full fkex flex-col">
                    <img src = "../images/explore_inflammatory.png" class = "w-full rounded-xl"/>
              
                </div>    

        </div>
</div>



  
</div>