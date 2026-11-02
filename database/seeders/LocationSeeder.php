<?php

namespace Database\Seeders;

use App\Models\User\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'تهران' => [
                'slug' => 'tehran',
                'cities' => [
                    'تهران' => 'tehran',
                    'ری' => 'rey',
                    'شمیرانات' => 'shemiranat',
                    'اسلامشهر' => 'eslamshahr',
                    'شهریار' => 'shahriar',
                    'قدس' => 'qods',
                    'ملارد' => 'malard',
                    'پردیس' => 'pardis',
                    'دماوند' => 'damavand',
                    'فیروزکوه' => 'firuzkuh',
                    'پاکدشت' => 'pakdasht',
                    'قرچک' => 'qarchak',
                    'ورامین' => 'varamin',
                ],
            ],
            'اصفهان' => [
                'slug' => 'isfahan',
                'cities' => [
                    'اصفهان' => 'isfahan',
                    'کاشان' => 'kashan',
                    'نجف‌آباد' => 'najafabad',
                    'خمینی‌شهر' => 'khomeynishahr',
                    'شاهین‌شهر' => 'shahinshahr',
                    'فلاورجان' => 'falavarjan',
                    'مبارکه' => 'mobarakeh',
                    'زرین‌شهر' => 'zarrinshahr',
                    'نطنز' => 'natanz',
                    'اردستان' => 'ardestan',
                ],
            ],
            'فارس' => [
                'slug' => 'fars',
                'cities' => [
                    'شیراز' => 'shiraz',
                    'مرودشت' => 'marvdasht',
                    'کازرون' => 'kazerun',
                    'جهرم' => 'jahrom',
                    'لار' => 'lar',
                    'فسا' => 'fasa',
                    'داراب' => 'darab',
                    'آباده' => 'abadeh',
                ],
            ],
            'خراسان رضوی' => [
                'slug' => 'khorasan-razavi',
                'cities' => [
                    'مشهد' => 'mashhad',
                    'نیشابور' => 'neyshabur',
                    'سبزوار' => 'sabzevar',
                    'تربت حیدریه' => 'torbat-e-heydarieh',
                    'کاشمر' => 'kashmar',
                    'قوچان' => 'quchan',
                    'تایباد' => 'taybad',
                ],
            ],
            'آذربایجان شرقی' => [
                'slug' => 'azerbaijan-sharghi',
                'cities' => [
                    'تبریز' => 'tabriz',
                    'مراغه' => 'maragheh',
                    'مرند' => 'marand',
                    'میانه' => 'mianeh',
                    'اهر' => 'ahar',
                    'بناب' => 'bonab',
                ],
            ],
            'آذربایجان غربی' => [
                'slug' => 'azerbaijan-gharbi',
                'cities' => [
                    'ارومیه' => 'urmia',
                    'خوی' => 'khoy',
                    'میاندوآب' => 'miandoab',
                    'مهاباد' => 'mahabad',
                    'بوکان' => 'bukan',
                    'پیرانشهر' => 'piranshahr',
                ],
            ],
            'البرز' => [
                'slug' => 'alborz',
                'cities' => [
                    'کرج' => 'karaj',
                    'فردیس' => 'fardis',
                    'نظرآباد' => 'nazarabad',
                    'هشتگرد' => 'hashtgerd',
                    'طالقان' => 'taleqan',
                ],
            ],
            'گیلان' => [
                'slug' => 'gilan',
                'cities' => [
                    'رشت' => 'rasht',
                    'انزلی' => 'anzali',
                    'لاهیجان' => 'lahijan',
                    'لنگرود' => 'langarud',
                    'رودسر' => 'rudsar',
                    'آستارا' => 'astara',
                ],
            ],
            'مازندران' => [
                'slug' => 'mazandaran',
                'cities' => [
                    'ساری' => 'sari',
                    'بابل' => 'babol',
                    'آمل' => 'amol',
                    'قائم‌شهر' => 'qaemshahr',
                    'نوشهر' => 'nowshahr',
                    'چالوس' => 'chalus',
                    'تنکابن' => 'tonekabon',
                ],
            ],
            'خوزستان' => [
                'slug' => 'khuzestan',
                'cities' => [
                    'اهواز' => 'ahvaz',
                    'آبادان' => 'abadan',
                    'خرمشهر' => 'khorramshahr',
                    'دزفول' => 'dezful',
                    'شوشتر' => 'shushtar',
                    'اندیمشک' => 'andimeshk',
                    'ماهشهر' => 'mahshahr',
                ],
            ],
            'قم' => [
                'slug' => 'qom',
                'cities' => [
                    'قم' => 'qom',
                ],
            ],
            'قزوین' => [
                'slug' => 'qazvin',
                'cities' => [
                    'قزوین' => 'qazvin',
                    'الوند' => 'alvand',
                    'آبیک' => 'abyek',
                    'تاکستان' => 'takestan',
                ],
            ],
            'کرمان' => [
                'slug' => 'kerman',
                'cities' => [
                    'کرمان' => 'kerman',
                    'سیرجان' => 'sirjan',
                    'رفسنجان' => 'rafsanjan',
                    'بم' => 'bam',
                    'جیرفت' => 'jiroft',
                ],
            ],
            'کرمانشاه' => [
                'slug' => 'kermanshah',
                'cities' => [
                    'کرمانشاه' => 'kermanshah',
                    'اسلام‌آباد غرب' => 'eslamabad-e-gharb',
                    'سنقر' => 'songhor',
                    'هرسین' => 'harsin',
                ],
            ],
            'همدان' => [
                'slug' => 'hamedan',
                'cities' => [
                    'همدان' => 'hamedan',
                    'ملایر' => 'malayer',
                    'نهاوند' => 'nahavand',
                    'تویسرکان' => 'tuyserkan',
                ],
            ],
            'یزد' => [
                'slug' => 'yazd',
                'cities' => [
                    'یزد' => 'yazd',
                    'میبد' => 'meybod',
                    'اردکان' => 'ardakan',
                    'بافق' => 'bafq',
                ],
            ],
            'هرمزگان' => [
                'slug' => 'hormozgan',
                'cities' => [
                    'بندرعباس' => 'bandar-abbas',
                    'قشم' => 'qeshm',
                    'کیش' => 'kish',
                    'میناب' => 'minab',
                ],
            ],
            'سیستان و بلوچستان' => [
                'slug' => 'sistan-va-baluchestan',
                'cities' => [
                    'زاهدان' => 'zahedan',
                    'چابهار' => 'chabahar',
                    'ایرانشهر' => 'iranshahr',
                    'سراوان' => 'saravan',
                    'خاش' => 'khash',
                ],
            ],
            'بوشهر' => [
                'slug' => 'bushehr',
                'cities' => [
                    'بوشهر' => 'bushehr',
                    'برازجان' => 'borazjan',
                    'گناوه' => 'genaveh',
                    'دیر' => 'deyr',
                ],
            ],
            'گلستان' => [
                'slug' => 'golestan',
                'cities' => [
                    'گرگان' => 'gorgan',
                    'گنبد کاووس' => 'gonbad-e-kavus',
                    'علی‌آباد کتول' => 'aliabad-e-katul',
                    'آق‌قلا' => 'aqqala',
                ],
            ],
            'اردبیل' => [
                'slug' => 'ardabil',
                'cities' => [
                    'اردبیل' => 'ardabil',
                    'مشگین‌شهر' => 'meshginshahr',
                    'پارس‌آباد' => 'parsabad',
                    'خلخال' => 'khalkhal',
                ],
            ],
            'زنجان' => [
                'slug' => 'zanjan',
                'cities' => [
                    'زنجان' => 'zanjan',
                    'ابهر' => 'abhar',
                    'خرمدره' => 'khorramdarreh',
                ],
            ],
            'ایلام' => [
                'slug' => 'ilam',
                'cities' => [
                    'ایلام' => 'ilam',
                    'دهلران' => 'dehlran',
                    'مهران' => 'mehran',
                ],
            ],
            'کهگیلویه و بویراحمد' => [
                'slug' => 'kohgiluyeh-va-boyer-ahmad',
                'cities' => [
                    'یاسوج' => 'yasuj',
                    'دوگنبدان' => 'do-gonbadan',
                    'دهدشت' => 'dehdasht',
                ],
            ],
            'چهارمحال و بختیاری' => [
                'slug' => 'chaharmahal-va-bakhtiari',
                'cities' => [
                    'شهرکرد' => 'shahrekord',
                    'بروجن' => 'borujen',
                    'فارسان' => 'farsan',
                ],
            ],
            'خراسان شمالی' => [
                'slug' => 'khorasan-shomali',
                'cities' => [
                    'بجنورد' => 'bojnurd',
                    'شیروان' => 'shirvan',
                    'اسفراین' => 'esfarayen',
                ],
            ],
            'خراسان جنوبی' => [
                'slug' => 'khorasan-jonubi',
                'cities' => [
                    'بیرجند' => 'birjand',
                    'قائن' => 'qaen',
                    'طبس' => 'tabas',
                ],
            ],
            'سمنان' => [
                'slug' => 'semnan',
                'cities' => [
                    'سمنان' => 'semnan',
                    'شاهرود' => 'shahroud',
                    'دامغان' => 'damghan',
                    'گرمسار' => 'garmsar',
                ],
            ],
            'مرکزی' => [
                'slug' => 'markazi',
                'cities' => [
                    'اراک' => 'arak',
                    'ساوه' => 'saveh',
                    'خمین' => 'khomeyn',
                    'محلات' => 'mahallat',
                ],
            ],
        ];

        foreach ($locations as $provinceName => $provinceData) {
            $province = Location::create([
                'name' => $provinceName,
                'slug' => $provinceData['slug'],
                'type' => Location::TYPE_PROVINCE,
                'parent_id' => null,
            ]);

            foreach ($provinceData['cities'] as $cityName => $citySlug) {
                Location::create([
                    'name' => $cityName,
                    'slug' => $citySlug,
                    'type' => Location::TYPE_CITY,
                    'parent_id' => $province->id,
                ]);
            }
        }
    }
}
