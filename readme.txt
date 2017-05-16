=== Top Trump Plugin ===
Contributors: Nick Braithwaite
Tested up to: 4.7.4

A Top Trump Card Game Generator,  takes the first .json file in /assets/data and then creates Top Trump Card data from it. Use Shortcode + category to output on your preferred page for example [top_trumps category=\"Singer Songwriters\"] based on the sample data installed. 

Any other top trump data must match format of /assets/data/singer-songwriter.json

== Description ==
Plugin will create a new post_type Top Trump & Taxonomy top_trumps_category. The data in the .json file is then installed into this post_type with each field becoming a meta value where the key is prefixed with the imported .json object name. 

== Installation ==
- Upload .zip file
- Activate the the plugin
- Place the shortcode [top_trumps category=\"xxxxxxx\"] on desired page for output.
- For demo data use [top_trumps category=\"Singer Songwriters\"]
