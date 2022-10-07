<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// Функция для разбиения ФИО на части
function getPartsFromFullname($personName)
{
    $parts = explode(" ", $personName);     // разделение ФИО по пробелам
    $parts['surname'] = $parts[0];          // перезапись фамилии, имени и отчества
    unset($parts[0]);                       // удаление старой записи
    $parts['name'] = $parts[1];
    unset($parts[1]);
    $parts['patronomyc'] = $parts[2];
    unset($parts[2]);
    return $parts;
}

// Функция для объединения ФИО из частей
function getFullnameFromParts($surname, $name, $patronomyc)
{
    $fullName = $surname . ' ' . $name . ' ' . $patronomyc;
    return $fullName;
}

// Функция для сокращения ФИО
function getShortName($personName)
{
    $Name = getPartsFromFullname($personName);  // Разбиение ФИО на части
    unset($Name['patronomyc']);                 // Удаление отчеств всех ФИО
    $firstSymbol = mb_substr($Name['name'], 0, 1, 'utf-8');     // Получение первой буквы имени
    $shortName = $Name['surname'] . " " . $firstSymbol . ".";   
    return $shortName;
}

// Функция определения пола по ФИО
function getGenderFromName($personName)
{
    $partsName = getPartsFromFullname($personName);
    $checkGender = 0;
    // Определение мужских признаков 
    if (mb_substr($partsName['surname'], -1, 1, 'utf-8')=== "в") {$checkGender ++;}
    if (mb_substr($partsName['name'], -1, 1, 'utf-8')=== "й" || mb_substr($partsName['name'], -1, 1, 'utf-8')=== "н" ) {$checkGender ++;}
    if (mb_substr($partsName['patronomyc'], -3, 3, 'utf-8')=== "вич") {$checkGender ++;}
    // Определение женских признаков
    if (mb_substr($partsName['surname'], -2, 2, 'utf-8')=== "ва"){$checkGender --;}
    if (mb_substr($partsName['name'], -1, 1, 'utf-8')=== "а"){$checkGender --;}
    if (mb_substr($partsName['patronomyc'], -3, 3, 'utf-8')=== "вна") {$checkGender --;}
    if ($checkGender>0) { return 1;}    // Мужчина
    if ($checkGender<0) { return -1; } // Женщина
    else { return 0;}                 // Не удалось определить
}

// Функция определение возрастно-полового состава
function getGenderDescription ($arrGender,$arrLenght)
{
    $countMale = 0;
    $countFemale = 0;
    $countUnknown = 0;
    // Подсчет кол-ва мужчин, женщин и не определенного пола
    foreach($arrGender as $value){ 
        if ($value == 1) {$countMale++;}
        if ($value == -1){$countFemale++;}
        if ($value == 0){$countUnknown++;}
    }
    // Процентное определение из всего количества человек
    $malePercent = ($countMale/$arrLenght)*100;
    $femalePercent = ($countFemale/$arrLenght)*100;
    $unknownPercent = ($countUnknown/$arrLenght)*100;
    echo 'Гендерный состав аудитории:';
    echo "<br>";
    echo "--------------------------------------";
    echo "<br>";
    echo "Мужчины" . " " . "-" . " " . round($malePercent) . "%";
    echo "<br>";
    echo "Женщины" . " " . "-" . " " . round($femalePercent) . "%";
    echo "<br>";
    echo "Не удалось определить" . " " . "-" . " " . round($unknownPercent) . "%";
    echo "<br>";
}


// Функция идеального подбора пары
function getPerfectPartner($surname, $name, $patronomyc,$partners_array)
{
    $personFullName = getFullnameFromParts($surname, $name, $patronomyc);   
    $genderPerson = getGenderFromName($personFullName);
    while ($genderPerson === 0){        // Проверка что пол распознан
        return 1;
    }
    // Выбор случайного партнера из массива
    $randomPartner = $partners_array[random_int(0, count($partners_array)-1)]['fullname'];
    // Проверка пола случайно выбранного партнера  
    $genderPartner = getGenderFromName($randomPartner);
    // Проверка на совпадение полов партнеров и распознание пола случайно выбранного партнера
    while ($genderPerson === $genderPartner || $genderPartner === 0 || $personFullName === $randomPartner)
    {
        $randomPartner = $partners_array[random_int(0, count($partners_array)-1)]['fullname'];
        $genderPartner = getGenderFromName($randomPartner);
    }
    $shortPersonName = getShortName($personFullName);
    $shortPartnerName = getShortName($randomPartner);
    $percentCompatibility = mt_rand(50, 100) + mt_rand(0, 100)/100; // Вычисление процента совместимости
    echo $shortPersonName . "+" . " " . $shortPartnerName . " " . "=" . "<br>". "♡" . "Идеально на" . " " . $percentCompatibility . "%". " " .  "♡";
    return 0;
}


// Вызов функции для разбиения ФИО на части (1 функция)
$arrParts = getPartsFromFullname($example_persons_array[2]['fullname']);

echo "!! Результат 1 функции: " . "<br>";
print_r($arrParts); // Вывод результата 1 функции 
echo "<br>". "<br>" ;



// Вызов функции для объединения ФИО из частей (параметры из вызова предыдущей функции) (2 функция)
$arrFullName = getFullnameFromParts($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc']);

echo "!! Результат 2 функции: " . "<br>";
print_r($arrFullName); // Вывод результата 2 функции
echo "<br>"."<br>";



// Вызов функции для сокращения ФИО (3 функция)
$nameShort = getShortName($example_persons_array[2]['fullname']);
echo "!! Результат 3 функции: " . "<br>";
print_r($nameShort); // Вывод результата 3 функции
echo "<br>"."<br>";



// Вызов функции для определения пола по ФИО (4 функция)
for ($i=0;$i<count($example_persons_array);$i++){
    // Определяется пол всех ФИО в массиве
$arrGender[$example_persons_array[$i]['fullname']] = getGenderFromName($example_persons_array[$i]['fullname']);
}
echo "!! Результат 4 функции: " . "<br>";
print_r($arrGender); // Вывод результата 4 функции
echo "<br>"."<br>";



// Вызов функции для определение возрастно-полового состава (5 функция)
echo "!! Результат 5 функции: " . "<br>";
getGenderDescription($arrGender, $arrLenght = count($example_persons_array));
echo "<br>";



// Вызов функции для идеального подбора пары
// Выбор случайного человека из массива и вызов функции для определения идеального партнера
echo "!! Результат 6 функции: " . "<br>";
$arrParts = getPartsFromFullname($example_persons_array[random_int(0, count($example_persons_array)-1)]['fullname']);
$choosePartner = getPerfectPartner($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc'],$example_persons_array);
while ($choosePartner === 1) // Если у выбранного человека пол не удалось определить, то снова идет выбор
{  
    $arrParts = getPartsFromFullname($example_persons_array[random_int(0, count($example_persons_array)-1)]['fullname']);
    $choosePartner = getPerfectPartner($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc'],$example_persons_array);
}

?>