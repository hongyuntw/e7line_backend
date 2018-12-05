<?php

use App\Category;
use App\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder_Real extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Category::truncate();
        Product::truncate();

        $category_id =
            [
                1,
                1,
                2,
                2,
                3,
                3,
                4,
                4,
                5,
                5,
                6,
                6,
                7,
                7,
                8,
                8,
                9,
                9,
                10,
                10,
                11,
                11,
                12,
                12,
                13,
                13,
                14,
                14,
                15,
                15,
                16,
                16,
            ];

        $name =
            [
                "Archipelago Brewery Singapore Blonde",
                "Brewdog Dead Pony Pale Ale",
                "Singha Lager Beer",
                "Tsingtao Pure Draft Beer",
                "Lindemans Cassis Lambic",
                "Timmermans Tradition Gueuze Lambic",
                "Dekuyper Peach Brandy",
                "Carlos I Brandy de Jerez Solera",
                "Glenlivet 12 Year Old Malt Whisky",
                "WOLFBURN Northland Original Of 46%",
                "Captain Morgan Original Spiced Rum",
                "Real Rum Blanco",
                "MZCL",
                "Marca Negra Dobadan Mezcal",
                "Essential Gin",
                "Tanqueray Export Strength Dry Gin",
                "Absolut Vodka",
                "Smirnoff Ice Vodka Premix Drink",
                "Greystone Sauvignon Blanc 2017",
                "Sauvignon Blanc, Pays d’Oc IGP",
                "Barefoot California Riesling Wine",
                "Most Wanted",
                "FITVINE Cabernet Sauvignon",
                "Justin Cabernet Sauvignon",
                "Merlet and Fils Triple Sec",
                "Merlet Creme de Poire William",
                "Cono Sur Bicicleta Pinot Noir",
                "Wyatt Pinot Noir 2016",
                "Cabos Glass Assorted Glassware Set",
                "Ladies Champagne Flute 2018",
                "Wineware Modern Wine Bucket",
                "Wine Opener - Old York",
            ];

        $listprice =
            [
                120,
                180,
                130,
                120,
                100,
                90,
                1500,
                2000,
                2000,
                2999,
                1500,
                1899,
                2900,
                2850,
                1200,
                900,
                450,
                50,
                800,
                500,
                700,
                800,
                500,
                300,
                1200,
                800,
                1500,
                700,
                700,
                2800,
                600,
                300,

            ];

        $unit =
            [
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Bottle",
                "Pack",
                "Pack",
                "Bucket",
                "Pack",
            ];

        $description =
            [
                "Singapore Blonde Ale 6-bottle Pack is a filtered blonde malt ale with a tangy, natural citrus taste and a hint of sweetness specially brewed with Calamansi Lime and Pandan Leaves. SBA is utterly refreshing with grilled meats, salads and spicy dishes.",
                "Brewdog Dead Pony Pale Ale.",
                "Singha Lager only uses the finest quality barley, malt and hops that are imported from around the world. These products are then blended with pure Artisian water producing a product pale yellow in colour, distinctively rich in flavor with strong hop and slightly spicy characters.",
                "In 1903, beer-savvy settlers from Germany and Great Britain founded Tsingtao Brewery in Qingdao, China. What makes Tsingtao great is the fresh spring water from Laoshan Mountain, the same place China's ancient religious belief Taoism originated. Today Tsingtao is the #1 Chinese beer in the U.S. and the #1 consumer product exported from China. Product of China",
                "Lindemans Cassis is a lambic made from local barley, unmalted wheat, and wild yeast. After spontaneous fermentation the lambic is aged in oak. Black currants are added creating a secondary fermentation and yielding an ale of exceptional flavor and complexity.",
                "No description available",
                "Savor sweet moments with our seven brandy flavors. From Blackberry Brandy as an after dinner treat to striking up a good conversation over Coffee Brandy, there's always an occasion to sit back and enjoy.",
                "Carlos I Brandy de Jerez Solera Gran Reserva is the epitome of noble Spanish brandies.",
                "Prominent aromas of ripe pear, vanilla and smooth honeycomb honey with a background presence of spicy orange peel zest and delicate hazelnut oak notes. Bursting with vibrant fruit, honey and pear nates, married with rich flavours of dark chocolate ginger, cinnamon and hints of liquorice root. Lingering vibrant and harmonious finish.",
                "Wolfburn is the most northerly whisky distillery on the scottish mainland. Originally founded in 1821, in its day it was one of the biggest in Scotland.",
                "Captain Morgan is the original party spirit, a spiced rum that was born and blended to have a good time. So grab a bottle of Captain Morgan Spiced Rum, raise a leg, and toast to the adventure to be had. Captain’s orders!",
                "Real Rum is a genuine Caribbean rum, produced in the Dominican Republic. It’s a classical strong drink for those who appreciate the real values of life. Real Rum is the rum just as it is – without many words or legends. It’s an excellent choice for mixing cocktails and a guarantee to live it up.",
                "MZCL, probably better known as Mezcal, is a spirit derived from the agave plant, which is native to Mexico. It is renowned as a special drink due to its extensive and time consuming production process. Salt & Silver commit to many characteristics that make MZCL unique and interesting – a cool, dynamic label outside the bottle, a prestigious and extremely well crafted drink inside the bottle. MZCL is both modern and nostalgic at the same time.",
                "Distilled from Agave rhodacantha, aka Mexicano, and bottled at 48% ABV. Truly wonderful, palate is full of ripe banana and juicy grilled pineapple, with light touches of spices like cinnamon and nutmeg. Rich and mouthfilling, with robust fruit and sweet agave.",
                "Our premium 100% field-to-flask Gin is handcrafted at our flagship distillery in Vernon to showcase the Okanagan terroir.",
                "The Tanqueray Export Quality London Dry Gin is made with four distinct botanicals for an unrivalled flavour (juniper, coriander, wild celery and liquorice). A four step distillation process is involved in the making of Tanqueray London Dry Gin. The only flavours you will get after the distillation are those of the botanicals with only strength from the neutral spirit. Mix with ice, tonic and lime.",
                "Absolut Vodka is the leading brand of Premium vodka offering the true taste of vodka in original or your favorite flavors made from natural ingredients.",
                "Made with Smirnoff vodka. With the classic taste of lemon. Premium ice triple filtered vodka mixed drink Drink ice cold.",
                "Clear pale lemon in colour.  Opulent aromas of pineapple, tropical fruits, lemon balm and fresh cut herbs.  Expect a generous and textual mouthfeel from the barrel ferment, minerality, juicy acidity and soft phenolics on the back palate.",
                "This wine is part of Baron Philippe de Rothschild’s pays d’Oc varietal range that comprises of seven varietal wines. This Sauvignon Blanc is a staff favourite and offers a crisp, aromatic and fashionable style. The diverse micro-climates of the Languedoc accommodates for the seven varietals across several parcels of land in and around Limoux.",
                "This is a lovely wine with tasty aromas and flavors of Mandarin orange and tangerine, layered with luscious peach & juicy pear. Hints of jasmine and honey complement the sweet and refreshing finish. Perfect match with white cheeses, fruit, appetizers and spicy cuisine. Yummy!",
                "No description available.",
                "Classic Cabernet nose of currant, lavender, black licorice and a hint of new oak. Rich purpose soft tannins wrap around flavors of cedar, boysenberry and coffee, chocolate with a hint of leather. Full flavored, clean taste with a smooth finish.",
                "At JUSTIN®, we combine traditional Old World methods—like hand-harvesting and small-barrel aging in French oak—with New World technology. For example: the “Air Knife,” an ingenious process invented by our winemakers, boosts grape quality and efficiency.",
                "Merlet Triple Sec is an orange flavoured liqueur made by macerating oranges and other citrus fruits in alcohol and then distilling before hydrating and sweetening with sugar.",
                "Merlet's lightly coloured crème de poire, traditionally made with real Williams pears and bursting with flavour. Merlet can trace their beginnings back to 1850 when Firmin Merlet started producing using his pot still.",
                "The Cono Sur vineyard workers travel around our estate by bicycle, tending the vines using natural methods, in order to produce the best quality grapes. Our Bicicleta wines are a tribute to them.",
                "A wonderfully lush, fruit-driven Pinot Noir, full and round on the palate that finishes beautifully. It drinks well on its own and is easy to match with meat, chicken, and even some fish dishes.",
                "Complete your Pinterest-perfect dinner or party plan with a complete set of 8 rocks glasses 8 and cooler glasses. The cool, sleek design is as comfortable as it is interesting. Which means when your guests’ mouths drop at the sight of your spread - their glasses won’t.",
                "The Lismore ‘Nine Ladies Dancing’ Flute is part of the prestige seasonal gifts uniting two age old traditions; the much loved 12 Days of Christmas carol and Waterford’s iconic Lismore pattern, a best-selling design since 1952. This luxurious crystal champagne flute, with the nine ladies dancing unique engraved design, is presented with a wine charm and celebratory red packaging making a wonderful collectable gift in the elegant 12 Days series which completes this year.",
                "Great for chilling your White Wine and Rose, add some cold water and ice and it sits perfectly on a dinner table.",
                "This double hinged corkscrew wine opener features the Old York Cellars' logo on handle.",
            ];

        $total = 20;
        foreach (range(0, 31) as $id) {
            Product::create([
                'category_id' => $category_id[$id],
                'name' => $name[$id],
                'listprice' => $listprice[$id],
                'saleprice' => $listprice[$id] * (rand(5, 10)*0.1) ,
                'imagename' => ($id+1).'.jpeg',
                'unit' => $unit[$id],
                'description' => $description[$id],
                'created_at' => now()->subDays($total - $id)->addHours(rand(1, 5))->addMinutes(rand(1, 5)),
                'updated_at' => now()->subDays($total - $id)->addHours(rand(6, 10))->addMinutes(rand(10, 30)),
            ]);
        }
    }
}
