document.addEventListener('DOMContentLoaded', function() {
    // Initialize Alpine.js data for the articles carousel
    window.carousel = function() {
        return {
            articles: [
                {
                    article_id: 1,
                    article_title: 'Foods to Avoid During Fasting',
                    article_thumbnail: '/images/unhealthyFoods.png',
                    article_tag: 'Nutrition'
                },
                {
                    article_id: 2,
                    article_title: 'Healthy Foods to Break Your Fast',
                    article_thumbnail: '/images/healthyfoodsArticle.png',
                    article_tag: 'Recommendations'
                }
            ],
            loading: false,
            currentIndex: 0,
            
            init() {
                // Auto-rotate every 5 seconds
                setInterval(() => {
                    if (!document.hidden) {
                        this.next();
                    }
                }, 5000);
            },
            
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.articles.length;
            },
            
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.articles.length) % this.articles.length;
            }
        };
    };
    
    // Initialize Alpine.js data for the recipes carousel
    window.recipeCarousel = function() {
        return {
            loading: false,
            currentIndex: 0,
            totalRecipes: document.querySelectorAll('.recipe-carousel-item')?.length || 0,
            
            init() {
                this.totalRecipes = document.querySelectorAll('.recipe-carousel-item')?.length || 0; // Replace 0 with the actual count if available
                // Auto-rotate every 6 seconds (slightly offset from articles)
                setInterval(() => {
                    if (!document.hidden) {
                        this.next();
                    }
                }, 6000);
            },
            
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.totalRecipes;
            },
            
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.totalRecipes) % this.totalRecipes;
            }
        };
    };

    // Update the tag filter functionality with the specific green color

    // Tag filter functionality
    const tagLinks = document.querySelectorAll('.tag-filter');
    const articles = document.querySelectorAll('.filtered-article');

    tagLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class and custom styling from all filters
            tagLinks.forEach(el => {
                el.classList.remove('bg-customGreen', 'text-white');
                el.style.backgroundColor = ''; // Reset any inline background color
                el.style.color = ''; // Reset text color
            });
            
            // Add custom styling to the clicked filter with the specific green color
            this.style.backgroundColor = '#5DB996';
            this.style.color = 'white';
            
            const selectedTag = this.getAttribute('data-tag');
            
            // Show/hide articles based on selected tag
            articles.forEach(article => {
                const articleTag = article.getAttribute('data-tag');
                
                if (selectedTag === 'all' || selectedTag === articleTag) {
                    article.style.display = 'block';
                } else {
                    article.style.display = 'none';
                }
            });
        });
    });

    // Activate "All" filter by default with the specific green color
    const allFilter = document.querySelector('.tag-filter[data-tag="all"]');
    if (allFilter) {
        allFilter.style.backgroundColor = '#5DB996';
        allFilter.style.color = 'white';
    }
});