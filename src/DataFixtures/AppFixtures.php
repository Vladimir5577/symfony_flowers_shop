<?php

namespace App\DataFixtures;

use App\Entity\BudgetTier;
use App\Entity\Category;
use App\Entity\HomeProduct;
use App\Entity\Occasion;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Enum\ProductBadge;
use App\Enum\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // === Categories ===
        $categories = [
            ['bouquets', 'Авторские букеты', ProductType::Flower],
            ['roses', 'Розы', ProductType::Flower],
            ['duo', 'Дуо-букеты', ProductType::Flower],
            ['mono', 'Монобукеты', ProductType::Flower],
            ['box', 'Букеты в коробке', ProductType::Flower],
            ['bride', 'Букет невесты', ProductType::Flower],
            ['tulips', 'Тюльпаны', ProductType::Flower],
            ['balloons', 'Шары на гелии', ProductType::Flower],
            ['plush', 'Игрушки', ProductType::Gift],
            ['candles', 'Свечи', ProductType::Gift],
            ['sweets', 'Сладкое', ProductType::Gift],
            ['cards', 'Открытки', ProductType::Gift],
            ['sets', 'Наборы', ProductType::Gift],
        ];

        $catEntities = [];
        foreach ($categories as $i => [$slug, $name, $type]) {
            $cat = new Category();
            $cat->setSlug($slug)->setName($name)->setType($type)->setSortOrder($i);
            $manager->persist($cat);
            $catEntities[$slug] = $cat;
        }

        // === Occasions ===
        $occasions = [
            ['girlfriend', 'Девушке', '💗'],
            ['wife', 'Жене', '💍'],
            ['mom', 'Маме', '🌷'],
            ['grandma', 'Бабушке', '🌹'],
            ['friend', 'Подруге', '🤍'],
            ['colleague', 'Коллеге', '👔'],
            ['teacher', 'Учителю', '📚'],
            ['birthday', 'День рождения', '🎂'],
            ['anniversary', 'Юбилей', '🥂'],
            ['graduation', 'Выпускной', '🎓'],
            ['wedding', 'Свадьба', '💒'],
            ['sorry', 'Извинения', '🙏'],
            ['wow', 'ШОК', '🤯'],
            ['justbecause', 'Просто так', '🌸'],
        ];

        $occEntities = [];
        foreach ($occasions as $i => [$slug, $name, $emoji]) {
            $occ = new Occasion();
            $occ->setSlug($slug)->setName($name)->setEmoji($emoji)->setSortOrder($i);
            $manager->persist($occ);
            $occEntities[$slug] = $occ;
        }

        // === Budget Tiers ===
        $budgetTiers = [
            ['budget_1500', 'до 1 500 ₽', 'Небольшой, но трогательный', 0, 1500],
            ['budget_3000', '1 500 — 3 000 ₽', 'Приятный сюрприз', 1500, 3000],
            ['budget_4500', '3 000 — 4 500 ₽', 'Авторская композиция', 3000, 4500],
            ['budget_6000', '4 500 — 6 000 ₽', 'Роскошный букет', 4500, 6000],
            ['budget_7500', '6 000 — 7 500 ₽', 'Особый повод', 6000, 7500],
            ['budget_9000', '7 500 — 9 000 ₽', 'Максимальное впечатление', 7500, 9000],
            ['budget_10500', '9 000 — 10 500 ₽', 'Премиум класс', 9000, 10500],
            ['budget_12000', '10 500 — 12 000 ₽', 'Эксклюзив', 10500, 12000],
            ['budget_13500', '12 000 — 13 500 ₽', 'Шик и роскошь', 12000, 13500],
            ['budget_15000', 'от 15 000 ₽', 'Безграничный сюрприз', 13500, 99999],
        ];

        $btEntities = [];
        foreach ($budgetTiers as $i => [$slug, $label, $desc, $min, $max]) {
            $bt = new BudgetTier();
            $bt->setSlug($slug)->setLabel($label)->setDescription($desc)
                ->setMinPrice($min)->setMaxPrice($max)->setSortOrder($i);
            $manager->persist($bt);
            $btEntities[$slug] = $bt;
        }

        // === Products ===
        $products = [
            ['bq-001', 'Нежный рассвет', 'Авторский букет «Нежный рассвет»',
                'Нежная композиция из пионовидных роз, эустомы и матиолы в пастельных тонах.',
                3500, 'bouquets', ProductType::Flower, ProductBadge::Hit, 'Нежно',
                ['girlfriend', 'wife', 'birthday', 'anniversary'], ['budget_4500', 'budget_6000'],
                false, false, ['pexels-tara-winstead-7666495.jpg', 'pexels-shvetsa-5750214.jpg']],

            ['bq-002', 'Летний бриз', 'Авторский букет «Летний бриз»',
                'Яркий и жизнерадостный букет из ромашек, подсолнухов и полевых цветов.',
                2800, 'bouquets', ProductType::Flower, ProductBadge::New, 'Ярко',
                ['friend', 'birthday', 'wow'], ['budget_3000', 'budget_4500'],
                false, false, ['pexels-karola-g-4466545.jpg', 'pexels-hbozman-1058771.jpg']],

            ['bq-003', 'Элегия', 'Авторский букет «Элегия»',
                'Изысканная монохромная композиция из белых роз и гортензий с зеленью.',
                5200, 'bouquets', ProductType::Flower, ProductBadge::Choice, 'Строго',
                ['anniversary', 'wife', 'mom'], ['budget_4500', 'budget_6000'],
                false, false, ['pexels-1579687-3972697.jpg', 'pexels-printproper-2831040.jpg']],

            ['bq-004', 'Карамель', 'Авторский букет «Карамель»',
                'Тёплый осенний букет из коралловых роз, оранжевых хризантем и золотистой зелени.',
                3200, 'bouquets', ProductType::Flower, null, 'Тепло',
                ['mom', 'friend', 'birthday'], ['budget_3000', 'budget_4500'],
                false, false, ['pexels-secret-garden-333350-931147.jpg', 'pexels-secret-garden-333350-2879814.jpg']],

            ['bq-005', 'Прощение', 'Авторский букет «Прощение»',
                'Нежный смешанный букет из белых и кремовых цветов.',
                2900, 'bouquets', ProductType::Flower, null, 'Нежно',
                ['sorry', 'girlfriend', 'wife'], ['budget_3000', 'budget_4500'],
                false, false, ['pexels-printproper-2831040.jpg']],

            ['ro-001', '25 красных роз', '25 красных роз (Кения, 60 см)',
                'Классика, которая никогда не выходит из моды. 25 алых роз премиального качества.',
                3900, 'roses', ProductType::Flower, ProductBadge::Hit, null,
                ['girlfriend', 'wife', 'anniversary', 'wow'], ['budget_4500', 'budget_6000'],
                false, false, ['pexels-iriser-1233414.jpg']],

            ['ro-002', '51 роза микс', '51 роза пастельный микс (50 см)',
                'Невероятный объём из 51 розы в нежных пастельных оттенках.',
                6800, 'roses', ProductType::Flower, ProductBadge::Max, null,
                ['girlfriend', 'wife', 'anniversary', 'wow'], ['budget_6000', 'budget_7500'],
                false, false, ['pexels-pixworthmedia-3117159.jpg']],

            ['ro-003', '11 розовых роз', '11 розовых роз (Эквадор, 50 см)',
                'Сочные розовые розы сорта «Пинк Флойд» на длинных стеблях.',
                2100, 'roses', ProductType::Flower, null, null,
                ['girlfriend', 'friend', 'birthday', 'sorry'], ['budget_3000'],
                false, false, ['pexels-valeriiamiller-3910065.jpg']],

            ['du-001', 'Роза + тюльпан', 'Дуо «Роза и тюльпан»',
                'Необычное сочетание изысканных роз и нежных тюльпанов.',
                2600, 'duo', ProductType::Flower, ProductBadge::New, 'Нежно',
                ['girlfriend', 'friend', 'birthday'], ['budget_3000', 'budget_4500'],
                false, false, ['pexels-secret-garden-333350-2879813.jpg']],

            ['mo-001', 'Хризантемы', 'Монобукет из хризантем',
                '15 крупных хризантем в натуральной упаковке крафт.',
                1800, 'mono', ProductType::Flower, null, null,
                ['mom', 'friend', 'birthday'], ['budget_1500', 'budget_3000'],
                false, false, ['pexels-hbozman-1058771.jpg']],

            ['bo-001', 'Роза в шляпной', 'Розы в шляпной коробке',
                'Стильная шляпная коробка с 15 розами и декоративной зеленью.',
                4200, 'box', ProductType::Flower, ProductBadge::Hit, null,
                ['girlfriend', 'wife', 'mom', 'anniversary'], ['budget_4500', 'budget_6000'],
                false, false, ['pexels-secret-garden-333350-931168.jpg']],

            ['br-001', 'Свадебный', 'Букет невесты «Воздушный»',
                'Нежный каскадный букет невесты из пионовидных роз, фрезий и эвкалипта.',
                7500, 'bride', ProductType::Flower, ProductBadge::Choice, null,
                ['wife', 'anniversary', 'wow'], ['budget_7500', 'budget_9000'],
                false, false, ['pexels-coralbellestudios-759668.jpg']],

            ['tu-001', '25 тюльпанов', '25 тюльпанов (микс пастель)',
                'Сезонные тюльпаны в нежном пастельном миксе.',
                1600, 'tulips', ProductType::Flower, null, null,
                ['mom', 'friend', 'birthday', 'sorry'], ['budget_1500', 'budget_3000'],
                false, false, ['pexels-brigitte-tohm-36757-350349.jpg']],

            ['ba-001', 'Набор шаров', 'Набор из 5 фольгированных шаров',
                'Яркий набор из 5 фольгированных шаров на гелии.',
                1200, 'balloons', ProductType::Flower, null, null,
                ['birthday', 'wow', 'friend'], ['budget_1500', 'budget_3000'],
                false, true, ['pexels-207983.jpg']],

            ['pl-001', 'Мишка 30 см', 'Плюшевый мишка 30 см (бежевый)',
                'Мягкий и нежный плюшевый мишка.',
                890, 'plush', ProductType::Gift, ProductBadge::Hit, null,
                ['girlfriend', 'friend', 'birthday', 'wow'], ['budget_1500'],
                true, true, ['pexels-167699.jpg']],

            ['pl-002', 'Зайка 40 см', 'Плюшевый зайка 40 см (розовый)',
                'Большой мягкий зайка с длинными ушами.',
                1200, 'plush', ProductType::Gift, null, null,
                ['girlfriend', 'friend', 'birthday'], ['budget_1500', 'budget_3000'],
                true, true, ['pexels-935018.jpg']],

            ['ca-001', 'Свеча ароматическая', 'Ароматическая свеча «Роза и ваниль»',
                'Натуральный соевый воск, аромат роз и ванили. Время горения 40 часов.',
                1500, 'candles', ProductType::Gift, ProductBadge::New, null,
                ['girlfriend', 'wife', 'mom', 'anniversary'], ['budget_1500', 'budget_3000'],
                true, true, ['pexels-5060816.jpg']],

            ['sw-001', 'Конфеты Raffaello', 'Набор конфет Raffaello, 150 г',
                'Классические конфеты Raffaello в красивой коробке.',
                650, 'sweets', ProductType::Gift, null, null,
                ['girlfriend', 'mom', 'friend', 'birthday', 'sorry'], ['budget_1500'],
                true, true, ['pexels-4117448.jpg']],

            ['cd-001', 'Открытка крафт', 'Открытка ручной работы (крафт)',
                'Стильная открытка из крафт-бумаги с красивым тиснением.',
                350, 'cards', ProductType::Gift, null, null,
                ['girlfriend', 'wife', 'mom', 'friend', 'birthday', 'anniversary', 'sorry'], ['budget_1500'],
                true, true, ['pexels-4913447.jpg']],

            ['se-001', 'Букет + мишка', 'Набор «Букет роз + плюшевый мишка»',
                'Готовый подарочный набор: авторский букет из роз и нежный плюшевый мишка 30 см.',
                4200, 'sets', ProductType::Gift, ProductBadge::Hit, null,
                ['girlfriend', 'friend', 'birthday', 'wow'], ['budget_4500', 'budget_6000'],
                true, false, ['pexels-zoedoingthings-2115971.jpg']],

            ['bq-006', 'Пудровое утро', 'Авторский букет «Пудровое утро»',
                'Нежная композиция в пудрово-кремовой гамме для тёплого признания.',
                3700, 'bouquets', ProductType::Flower, ProductBadge::New, 'Нежно',
                ['girlfriend', 'wife', 'justbecause'], ['budget_4500', 'budget_6000'],
                false, false, []],

            ['bq-007', 'Ягодный сорбет', 'Авторский букет «Ягодный сорбет»',
                'Яркий букет в малиново-розовых оттенках с акцентной упаковкой.',
                4100, 'bouquets', ProductType::Flower, null, 'Ярко',
                ['birthday', 'friend', 'wow'], ['budget_4500', 'budget_6000'],
                false, false, []],

            ['ro-004', '35 белых роз', '35 белых роз (Эквадор, 60 см)',
                'Эффектная белоснежная классика для свадьбы и важных событий.',
                5600, 'roses', ProductType::Flower, ProductBadge::Choice, null,
                ['wedding', 'anniversary', 'wife'], ['budget_6000', 'budget_7500'],
                false, false, []],

            ['ro-005', '15 роз микс', '15 роз микс (50 см)',
                'Небольшой, но выразительный микс роз в сезонной палитре.',
                2600, 'roses', ProductType::Flower, null, null,
                ['girlfriend', 'birthday', 'sorry'], ['budget_3000', 'budget_4500'],
                false, false, []],

            ['du-002', 'Пион + эустома', 'Дуо «Пион и эустома»',
                'Воздушная дуо-композиция для романтичного повода.',
                3400, 'duo', ProductType::Flower, ProductBadge::Hit, 'Нежно',
                ['girlfriend', 'wife', 'anniversary'], ['budget_4500', 'budget_6000'],
                false, false, []],

            ['mo-002', 'Гипсофила cloud', 'Монобукет из гипсофилы',
                'Лёгкий и объёмный монобукет, который выглядит как облако.',
                2200, 'mono', ProductType::Flower, ProductBadge::New, null,
                ['friend', 'birthday', 'justbecause'], ['budget_3000', 'budget_4500'],
                false, false, []],

            ['bo-002', 'Пион в коробке', 'Пионы в шляпной коробке',
                'Премиальная композиция из сезонных пионов в коробке.',
                5900, 'box', ProductType::Flower, ProductBadge::Max, null,
                ['wife', 'anniversary', 'wow'], ['budget_6000', 'budget_7500'],
                false, false, []],

            ['br-002', 'Невеста крем', 'Букет невесты «Кремовый шлейф»',
                'Сдержанный свадебный букет в кремовой палитре.',
                8200, 'bride', ProductType::Flower, null, null,
                ['wedding', 'wife'], ['budget_7500', 'budget_9000'],
                false, false, []],

            ['tu-002', '51 тюльпан', '51 тюльпан (яркий микс)',
                'Большой праздничный букет тюльпанов в насыщенных оттенках.',
                3400, 'tulips', ProductType::Flower, ProductBadge::Hit, 'Ярко',
                ['mom', 'birthday', 'wow'], ['budget_4500', 'budget_6000'],
                false, false, []],

            ['ba-002', 'Сердце из шаров', 'Композиция из шаров «Сердце»',
                'Романтичная композиция из гелиевых шаров в форме сердца.',
                1900, 'balloons', ProductType::Flower, null, null,
                ['girlfriend', 'anniversary', 'wow'], ['budget_1500', 'budget_3000'],
                false, true, []],

            ['pl-003', 'Панда 25 см', 'Плюшевая панда 25 см',
                'Компактная плюшевая панда для милого дополнения к букету.',
                990, 'plush', ProductType::Gift, null, null,
                ['friend', 'girlfriend', 'birthday'], ['budget_1500'],
                true, true, []],

            ['ca-002', 'Свеча peony', 'Ароматическая свеча «Peony Blush»',
                'Цветочный аромат с нотами пиона и белого мускуса.',
                1700, 'candles', ProductType::Gift, ProductBadge::Choice, null,
                ['wife', 'mom', 'justbecause'], ['budget_1500', 'budget_3000'],
                true, true, []],

            ['sw-002', 'Макаронс mini', 'Набор макаронс mini, 12 шт',
                'Нежные французские макаронс в ассорти вкусов.',
                1100, 'sweets', ProductType::Gift, ProductBadge::New, null,
                ['girlfriend', 'friend', 'birthday'], ['budget_1500', 'budget_3000'],
                true, true, []],

            ['cd-002', 'Открытка акварель', 'Открытка «Акварельные цветы»',
                'Минималистичная открытка с акварельной иллюстрацией.',
                390, 'cards', ProductType::Gift, null, null,
                ['birthday', 'anniversary', 'justbecause'], ['budget_1500'],
                true, true, []],

            ['se-002', 'Букет + свеча', 'Набор «Букет + ароматическая свеча»',
                'Готовый набор для уютного и тёплого подарка.',
                4600, 'sets', ProductType::Gift, ProductBadge::Hit, null,
                ['wife', 'mom', 'anniversary'], ['budget_4500', 'budget_6000'],
                true, false, []],
        ];

        // slug, name, fullName, description, price, category, type, badge, mood,
        // occasions, budgets, isGift, canBeCombined, photos
        $imagePool = [];
        foreach ($products as $productRow) {
            $imagePool = array_merge($imagePool, $productRow[13]);
        }
        $imagePool = array_values(array_unique($imagePool));
        $productEntitiesBySlug = [];

        foreach ($products as $i => $p) {
            [$slug, $name, $fullName, $description, $price, $category, $type, $badge, $mood,
                $occasionSlugs, $budgetSlugs, $isGift, $canBeCombined, $photos] = $p;

            $product = new Product();
            $product->setSlug($slug)
                ->setName($name)
                ->setFullName($fullName)
                ->setDescription($description)
                ->setPrice($price)
                ->setCategory($catEntities[$category])
                ->setType($type)
                ->setBadge($badge)
                ->setMood($mood)
                ->setInStock(true)
                ->setIsGift($isGift)
                ->setCanBeCombined($canBeCombined)
                ->setSortOrder($i);

            foreach ($occasionSlugs as $occSlug) {
                if (isset($occEntities[$occSlug])) {
                    $product->addOccasion($occEntities[$occSlug]);
                }
            }

            foreach ($budgetSlugs as $btSlug) {
                if (isset($btEntities[$btSlug])) {
                    $product->addBudgetTier($btEntities[$btSlug]);
                }
            }

            $photoCount = $isGift ? 1 : (1 + (crc32($slug) % 2));
            $photos = $this->pickRandomPhotos($imagePool, $slug, $photoCount);

            foreach ($photos as $j => $photoFile) {
                $image = new ProductImage();
                $image->setImageName($photoFile);
                $image->setSortOrder($j);
                $product->addImage($image);
                $manager->persist($image);
            }

            $manager->persist($product);
            $productEntitiesBySlug[$slug] = $product;
        }

        $homePageSlugs = [
            'bq-001',
            'ro-001',
            'bq-006',
            'bo-002',
            'se-001',
            'tu-002',
            'du-002',
            'ca-002',
            'ro-004',
            'bq-007',
            'br-001',
            'se-002',
        ];

        foreach ($homePageSlugs as $order => $slug) {
            if (!isset($productEntitiesBySlug[$slug])) {
                continue;
            }

            $homeProduct = new HomeProduct();
            $homeProduct->setProduct($productEntitiesBySlug[$slug]);
            $homeProduct->setSortOrder($order);
            $manager->persist($homeProduct);
        }

        $manager->flush();
    }

    /**
     * Берем "случайные" фото детерминированно, чтобы фикстуры были стабильны.
     */
    private function pickRandomPhotos(array $pool, string $seed, int $count): array
    {
        if ($pool === [] || $count <= 0) {
            return [];
        }

        $shuffled = $pool;
        usort($shuffled, static function (string $a, string $b) use ($seed): int {
            return strcmp(sha1($seed . $a), sha1($seed . $b));
        });

        return array_slice($shuffled, 0, min($count, count($shuffled)));
    }
}
