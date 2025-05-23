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

<div class="w-full overflow-hidden">    <img src="../images/unhealthyFoods.png" 
         class="w-full md:h-64 lg:h-80 object-cover object-center"
         alt="Healthy Foods Header Image"/>
</div>
<div class="flex flex-col items-center justify-center pb-20 p-2">

<div class="w-full max-w-4xl mx-auto p-4 space-y-6">
        <!-- Article Details -->
        <div class="bg-white rounded-lg shadow-md p-4">
            <ol class = "list-decimal pl-6">
                <li>Refined Sugar</li>
                <ul class="list-disc pl-6">
                    <li>pop, candy, pastries, cereals</li>
                </ul>
                <li>Refined Wheats</li>
                <ul class="list-disc pl-6">
                    <li>white bread/pasta/rice</li>
                </ul>

                <li>Trans-fat</li>
                <ul class = "list-disc pl-6">
                    <li>fast foods</li>
                    <li>processed/packaged foods</li>
            </ul>
            <li>High Sodium Content</li>
                <ul class = "list-disc pl-6">
                    <li>processed / packaged foods</li>
                    <li>mayo</li>
                    <li>certain cooking oils (sunflower oil, corn oil, soybean oil)</li>
                    <li>prepared salad dressings</li>
                </ul>
            </ol>
            <div class="flex flex-col gap-3">
                <h2><span class="text-lg font-semibold">Summary</span> </h2>
                <div class = "w-full fkex flex-col">
                    <img src = "../images/explore_inflammatory.png" class = "w-full rounded-xl"/>
                </div>     
                
                <h2><span class="text-lg font-semibold">Foods to Eat</span> </h2>
                <div class = "w-full fkex flex-col">
                    <img src = "../images/explore_anti_inflammatory.png" class = "w-full rounded-xl"/>
              
                </div>    

        </div>
</div>



  
</div>