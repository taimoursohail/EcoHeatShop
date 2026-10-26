(function() {
    'use strict';
    var schemas = [
        // LocalBusiness Schema (enhanced business info)
        {
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": "ECO HEAT SHOP LTD",
            "url": "https://www.ecoheatshop.co.uk/",
            "telephone": "+44 121 227 0770",
            "email": "info@ecoheatshop.co.uk",
            "legalName": "ECO Heat Shop LTD",
            "description": "ECO Heat Shop exists as a forward-thinking brand dedicated to delivering sustainable, energy-efficient heating and renewable energy solutions for homes and businesses across the UK.",
            "image": "https://www.ecoheatshop.co.uk/wp-content/uploads/2025/08/New-EHS-Logo.png", 
            "logo": {
                "@type": "ImageObject",
                "url": "https://www.ecoheatshop.co.uk/wp-content/uploads/2025/08/New-EHS-Logo.png"
            },
            "sameAs": [
                "https://www.facebook.com/ecoheatshop.uk/",
                "https://www.tiktok.com/@ecoheatshop"
            ],
            "identifier": {
                "@type": "PropertyValue",
                "name": "UK Company Number",
                "value": "16383897"
            },
            "priceRange": "$$",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Unit 8, West Cross Shopping Centre, Oldbury Road",
                "addressLocality": "Smethwick",
                "addressRegion": "West Midlands",
                "postalCode": "B66 1JG",
                "addressCountry": "UK"
            },
            "geo": {
                "@type": "GeoCoordinates",
                "latitude": 52.4965, // Approximate lat/long for Smethwick; get exact from Google Maps if needed
                "longitude": -1.9688
            },
            "openingHoursSpecification": [
                {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    "opens": "08:00",
                    "closes": "18:00"
                }
            ],
            "hasOfferCatalog": {
                "@type": "OfferCatalog",
                "name": "Heating and Renewable Energy Products and Services",
                "itemListElement": [
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "name": "Boilers",
                            "url": "https://www.ecoheatshop.co.uk/product-category/boilers/"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "name": "Radiators",
                            "url": "https://www.ecoheatshop.co.uk/product-category/radiators/"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "name": "Heatings",
                            "url": "https://www.ecoheatshop.co.uk/product-category/heating/"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "name": "Tools",
                            "url": "https://www.ecoheatshop.co.uk/product-category/tools/"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "name": "Renewable",
                            "url": "https://www.ecoheatshop.co.uk/product-category/renewables/"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "name": "Spare Parts",
                            "url": "https://www.ecoheatshop.co.uk/product-category/spares/"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "name": "Home & Building Supplies",
                            "url": "https://www.ecoheatshop.co.uk/product-category/household-garden-accessories/"
                        }
                    },
                    {
                        "@type": "Offer",
                        "itemOffered": {
                            "@type": "Service",
                            "name": "Boiler Installation Services",
                            "url": "https://www.ecoheatshop.co.uk/england-boiler-service/",
                            "offers": {
                                "@type": "Offer",
                                "price": "750",
                                "priceCurrency": "GBP"
                            }
                        }
                    }
                ]
            }
        },
        // WebSite Schema with Sitelinks Search Box
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "ECO Heat Shop LTD",
            "url": "https://www.ecoheatshop.co.uk/",
            "potentialAction": {
                "@type": "SearchAction",
                "target": {
                    "@type": "EntryPoint",
                    "urlTemplate": "https://www.ecoheatshop.co.uk/?s={search_term_string}&post_type=product&product_cat=0"
                },
                "query-input": "required name=search_term_string"
            }
        }
    ];

    var script = document.createElement('script');
    script.type = 'application/ld+json';
    script.textContent = JSON.stringify(schemas);
    document.head.appendChild(script);
})();