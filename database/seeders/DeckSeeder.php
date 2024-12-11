<?php

namespace Database\Seeders;

use App\Models\Deck;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First Deck: Japanese Numbers
        $firstDeck = Deck::create([
            'language' => 'Japanese',
            'deck_description' => 'Learning Japanese numbers from 1 - 10.',
        ]);

        $numbers = [
            1 => '一 (ichi)',
            2 => '二 (ni)',
            3 => '三 (san)',
            4 => '四 (shi/yon)',
            5 => '五 (go)',
            6 => '六 (roku)',
            7 => '七 (shichi/nana)',
            8 => '八 (hachi)',
            9 => '九 (kyuu)',
            10 => '十 (juu)',
        ];

        $englishTranslations = [
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
        ];

        foreach ($numbers as $num => $japanese) {
            $correctAnswer = "{$englishTranslations[$num - 1]}";
            $wrongAnswers = array_diff($englishTranslations, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $firstDeck->cards()->create([
                'content' => "Japanese of $num is ($japanese)",
                'question' => "What is {$japanese}?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Japanese Greetings
        $secondDeck = Deck::create([
            'language' => 'Japanese',
            'deck_description' => 'Learn common Japanese greetings and their meanings.',
        ]);

        $greetings = [
            'こんにちは (Konnichiwa)' => 'Hello',
            'おはようございます (Ohayou gozaimasu)' => 'Good morning',
            'こんばんは (Konbanwa)' => 'Good evening',
            'さようなら (Sayounara)' => 'Goodbye',
            'ありがとうございます (Arigatou gozaimasu)' => 'Thank you',
        ];

        $englishOptions = [
            'Hello',
            'Good morning',
            'Good evening',
            'Goodbye',
            'Thank you',
            'Sorry',
            'Yes',
            'No',
            'Please',
            'Good night',
        ];

        foreach ($greetings as $japanese => $correctAnswer) {
            $wrongAnswers = array_diff($englishOptions, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $secondDeck->cards()->create([
                'content' => "'{$japanese}' is a Japanese greeting that translates to '{$correctAnswer}' in English. It is commonly used to greet someone.",
                'question' => "What does '{$japanese}' mean in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Japanese Numbers (11-20)
        $thirdDeck = Deck::create([
            'language' => 'Japanese',
            'deck_description' => 'Learning Japanese numbers from 11 - 20.',
        ]);

        $numbers = [
            11 => '十一 (juu ichi)',
            12 => '十二 (juu ni)',
            13 => '十三 (juu san)',
            14 => '十四 (juu shi/yon)',
            15 => '十五 (juu go)',
            16 => '十六 (juu roku)',
            17 => '十七 (juu shichi/nana)',
            18 => '十八 (juu hachi)',
            19 => '十九 (juu kyuu)',
            20 => '二十 (nijuu)',
        ];

        $englishTranslations = [
            'Eleven',
            'Twelve',
            'Thirteen',
            'Fourteen',
            'Fifteen',
            'Sixteen',
            'Seventeen',
            'Eighteen',
            'Nineteen',
            'Twenty',
        ];

        foreach ($numbers as $num => $japanese) {
            $correctAnswer = "{$englishTranslations[$num - 11]}";
            $wrongAnswers = array_diff($englishTranslations, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $thirdDeck->cards()->create([
                'content' => "The Japanese for $num is '{$japanese}', which means '{$correctAnswer}' in English.",
                'question' => "What is {$japanese} in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Japanese Days of the Week
        $fourthDeck = Deck::create([
            'language' => 'Japanese',
            'deck_description' => 'Learn the Japanese names for the days of the week and their English meanings.',
        ]);

        $daysOfWeek = [
            '月曜日 (Getsuyoubi)' => 'Monday',
            '火曜日 (Kayoubi)' => 'Tuesday',
            '水曜日 (Suiyoubi)' => 'Wednesday',
            '木曜日 (Mokuyoubi)' => 'Thursday',
            '金曜日 (Kinyoubi)' => 'Friday',
            '土曜日 (Doyoubi)' => 'Saturday',
            '日曜日 (Nichiyoubi)' => 'Sunday',
        ];

        $englishDays = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        foreach ($daysOfWeek as $japanese => $correctAnswer) {
            $wrongAnswers = array_diff($englishDays, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $fourthDeck->cards()->create([
                'content' => "'{$japanese}' is the Japanese word for '{$correctAnswer}'. It represents a day of the week in Japan.",
                'question' => "What day of the week does '{$japanese}' represent?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Japanese Basic Phrases
        $fifthDeck = Deck::create([
            'language' => 'Japanese',
            'deck_description' => 'Learn common Japanese phrases for everyday conversations.',
        ]);

        $phrases = [
            'お元気ですか？ (Ogenki desu ka?)' => 'How are you?',
            'はい (Hai)' => 'Yes',
            'いいえ (Iie)' => 'No',
            'すみません (Sumimasen)' => 'Excuse me / I’m sorry',
            'よろしくお願いします (Yoroshiku onegaishimasu)' => 'Nice to meet you / Please take care of me',
            'わかりません (Wakarimasen)' => 'I don’t understand',
            'いくらですか？ (Ikura desu ka?)' => 'How much is it?',
            'トイレはどこですか？ (Toire wa doko desu ka?)' => 'Where is the bathroom?',
            'ありがとうございます (Arigatou gozaimasu)' => 'Thank you',
            '助けてください (Tasukete kudasai)' => 'Please help me',
        ];

        $englishOptions = [
            'How are you?',
            'Yes',
            'No',
            'Excuse me / I’m sorry',
            'Nice to meet you / Please take care of me',
            'I don’t understand',
            'How much is it?',
            'Where is the bathroom?',
            'Thank you',
            'Please help me',
            'Goodbye',
            'Hello',
            'What time is it?',
            'Can I have some water?',
        ];

        foreach ($phrases as $japanese => $correctAnswer) {
            $wrongAnswers = array_diff($englishOptions, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $fifthDeck->cards()->create([
                'content' => "The phrase '{$japanese}' is commonly used in Japanese and translates to '{$correctAnswer}' in English. It is an essential part of everyday conversation.",
                'question' => "What does '{$japanese}' mean in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Japanese Food Vocabulary
        $sixthDeck = Deck::create([
            'language' => 'Japanese',
            'deck_description' => 'Learn common Japanese words for food and their English meanings.',
        ]);

        $foodItems = [
            'りんご (Ringo)' => 'Apple',
            'ごはん (Gohan)' => 'Rice',
            '魚 (Sakana)' => 'Fish',
            '肉 (Niku)' => 'Meat',
            'パン (Pan)' => 'Bread',
            '野菜 (Yasai)' => 'Vegetables',
            '水 (Mizu)' => 'Water',
            'お茶 (Ocha)' => 'Tea',
            'コーヒー (Koohii)' => 'Coffee',
            'ケーキ (Keeki)' => 'Cake',
        ];

        $englishFoodOptions = [
            'Apple',
            'Rice',
            'Fish',
            'Meat',
            'Bread',
            'Vegetables',
            'Water',
            'Tea',
            'Coffee',
            'Cake',
            'Milk',
            'Juice',
            'Soup',
            'Egg',
            'Noodles',
        ];

        foreach ($foodItems as $japanese => $correctAnswer) {
            $wrongAnswers = array_diff($englishFoodOptions, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $sixthDeck->cards()->create([
                'content' => "'{$japanese}' is the Japanese word for '{$correctAnswer}', commonly used to refer to this type of food or drink.",
                'question' => "What does '{$japanese}' mean in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Japanese Common Sentences
        $seventhDeck = Deck::create([
            'language' => 'Japanese',
            'deck_description' => 'Learn commonly used Japanese sentences and their English translations.',
        ]);

        $sentences = [
            '私は学生です。 (Watashi wa gakusei desu.)' => 'I am a student.',
            'これはペンです。 (Kore wa pen desu.)' => 'This is a pen.',
            'あなたの名前は何ですか？ (Anata no namae wa nan desu ka?)' => 'What is your name?',
            'トイレに行ってもいいですか？ (Toire ni itte mo ii desu ka?)' => 'May I go to the bathroom?',
            '助けてください！ (Tasukete kudasai!)' => 'Please help me!',
            '今日はいい天気ですね。 (Kyou wa ii tenki desu ne.)' => 'It’s a nice day today, isn’t it?',
            '日本語を話せますか？ (Nihongo o hanasemasu ka?)' => 'Can you speak Japanese?',
            'これはいくらですか？ (Kore wa ikura desu ka?)' => 'How much is this?',
            'すみません、遅れました。 (Sumimasen, okuremashita.)' => 'Sorry, I am late.',
            'お腹が空きました。 (Onaka ga sukimashita.)' => 'I am hungry.',
        ];

        $englishSentences = [
            'I am a student.',
            'This is a pen.',
            'What is your name?',
            'May I go to the bathroom?',
            'Please help me!',
            'It’s a nice day today, isn’t it?',
            'Can you speak Japanese?',
            'How much is this?',
            'Sorry, I am late.',
            'I am hungry.',
            'I am tired.',
            'I like sushi.',
            'Where is the station?',
            'I don’t understand.',
            'What time is it?',
        ];

        foreach ($sentences as $japanese => $correctAnswer) {
            $wrongAnswers = array_diff($englishSentences, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $seventhDeck->cards()->create([
                'content' => "'{$japanese}' is a common Japanese sentence that means '{$correctAnswer}' in English. It is useful in everyday conversations.",
                'question' => "What does '{$japanese}' mean in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Korean Common Sentences
        $eightDeck = Deck::create([
            'language' => 'Korean',
            'deck_description' => 'Learn commonly used Korean sentences and their English translations.',
        ]);

        $sentences = [
            '안녕하세요? (Annyeonghaseyo?)' => 'Hello.',
            '제 이름은 [이름]입니다. (Je ireumeun Juan imnida.)' => 'My name is Juan.',
            '감사합니다. (Gamsahamnida.)' => 'Thank you.',
            '죄송합니다. (Joesonghamnida.)' => 'I am sorry.',
            '이것은 무엇입니까? (Igeoseun mueosimnikka?)' => 'What is this?',
            '화장실이 어디에 있습니까? (Hwajangsiri eodie itsseumnikka?)' => 'Where is the bathroom?',
            '얼마입니까? (Eolmaimnikka?)' => 'How much is it?',
            '도와주세요! (Dowajuseyo!)' => 'Please help me!',
            '괜찮아요. (Gwaenchanayo.)' => 'It’s okay.',
            '배고파요. (Baegopayo.)' => 'I am hungry.',
        ];

        $englishSentences = [
            'Hello.',
            'My name is Juan.',
            'Thank you.',
            'I am sorry.',
            'What is this?',
            'Where is the bathroom?',
            'How much is it?',
            'Please help me!',
            'It’s okay.',
            'I am hungry.',
            'I am thirsty.',
            'Goodbye.',
            'I like Korean food.',
            'What time is it?',
            'Where is the subway station?',
        ];

        foreach ($sentences as $korean => $correctAnswer) {
            $wrongAnswers = array_diff($englishSentences, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $eightDeck->cards()->create([
                'content' => "'{$korean}' is a common Korean sentence that translates to '{$correctAnswer}' in English. This phrase is often used in everyday conversations.",
                'question' => "What does '{$korean}' mean in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Korean Additional Common Sentences
        $ninthDeck = Deck::create([
            'language' => 'Korean',
            'deck_description' => 'Expand your Korean knowledge with more common sentences and their English meanings.',
        ]);

        $sentences = [
            '잘 지내세요? (Jal jinaeseyo?)' => 'How are you?',
            '이것을 주세요. (Igeoseul juseyo.)' => 'Please give me this.',
            '영어를 할 줄 아세요? (Yeongeoreul hal jul aseyo?)' => 'Do you speak English?',
            '문제를 이해했어요. (Munjeleul ihaehaesseoyo.)' => 'I understand the problem.',
            '천천히 말해주세요. (Cheoncheonhi malhaejuseyo.)' => 'Please speak slowly.',
            '기차역은 어디에 있습니까? (Gichayeogeun eodie itsseumnikka?)' => 'Where is the train station?',
            '나는 한국어를 배우고 있어요. (Naneun hangugeoreul baeugo isseoyo.)' => 'I am learning Korean.',
            '만나서 반가워요. (Mannaseo bangawoyo.)' => 'Nice to meet you.',
            '얼마나 멀리 있어요? (Eolmana meolli isseoyo?)' => 'How far is it?',
            '다시 한 번 말해주세요. (Dasi han beon malhaejuseyo.)' => 'Please say it again.',
        ];

        $englishSentences = [
            'How are you?',
            'Please give me this.',
            'Do you speak English?',
            'I understand the problem.',
            'Please speak slowly.',
            'Where is the train station?',
            'I am learning Korean.',
            'Nice to meet you.',
            'How far is it?',
            'Please say it again.',
            'I am lost.',
            'What is your phone number?',
            'Where can I find a taxi?',
            'Is it nearby?',
            'Can you help me with this?',
        ];

        foreach ($sentences as $korean => $correctAnswer) {
            $wrongAnswers = array_diff($englishSentences, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $ninthDeck->cards()->create([
                'content' => "'{$korean}' is a useful Korean sentence that translates to '{$correctAnswer}' in English. It is helpful in daily interactions.",
                'question' => "What does '{$korean}' mean in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }

        // New Deck: Korean Travel and Dining Sentences
        $tenthDeck = Deck::create([
            'language' => 'Korean',
            'deck_description' => 'Learn essential Korean sentences for travel and dining situations.',
        ]);

        $sentences = [
            '공항은 어디에 있습니까? (Gonghangeun eodie itsseumnikka?)' => 'Where is the airport?',
            '호텔 예약을 했습니다. (Hotel yeyageul haetseumnida.)' => 'I have a hotel reservation.',
            '추천 메뉴가 무엇인가요? (Chucheon menyuga mueosingayo?)' => 'What do you recommend on the menu?',
            '물이 필요해요. (Muri piryohaeyo.)' => 'I need water.',
            '포크가 있나요? (Pokeuga innayo?)' => 'Do you have a fork?',
            '이건 너무 매워요. (Igeon neomu maewoyo.)' => 'This is too spicy.',
            '계산서를 주세요. (Gyesanseoreul juseyo.)' => 'Please give me the bill.',
            '화장실은 어디에 있나요? (Hwajangsireun eodie innayo?)' => 'Where is the restroom?',
            '버스 정류장은 어디인가요? (Beoseu jeongryujangeun eodingayo?)' => 'Where is the bus stop?',
            '기차 시간표를 보여주세요. (Gicha siganpyoreul boyeojuseyo.)' => 'Please show me the train schedule.',
        ];

        $englishSentences = [
            'Where is the airport?',
            'I have a hotel reservation.',
            'What do you recommend on the menu?',
            'I need water.',
            'Do you have a fork?',
            'This is too spicy.',
            'Please give me the bill.',
            'Where is the restroom?',
            'Where is the bus stop?',
            'Please show me the train schedule.',
            'I am lost.',
            'Do you have vegetarian options?',
            'Where can I buy a ticket?',
            'Can you take a photo of me?',
            'How long does it take to get there?',
        ];

        foreach ($sentences as $korean => $correctAnswer) {
            $wrongAnswers = array_diff($englishSentences, [$correctAnswer]);
            shuffle($wrongAnswers);
            $choices = array_slice($wrongAnswers, 0, 3);
            $choices[] = $correctAnswer;
            shuffle($choices);

            $card = $tenthDeck->cards()->create([
                'content' => "'{$korean}' is a useful Korean sentence that translates to '{$correctAnswer}' in English. This phrase is essential for travel or dining interactions.",
                'question' => "What does '{$korean}' mean in English?",
            ]);

            foreach ($choices as $choice) {
                $card->choices()->create([
                    'choice' => $choice,
                    'isCorrect' => $choice === $correctAnswer,
                ]);
            }
        }
    }
}
