<?php
function acronym($string)
{
    $words = explode(" ", $string);
    $acronym = "";

    foreach ($words as $w) {
        $acronym .= mb_substr($w, 0, 1);
    }
    return $acronym;
}
function analyzeRiskCategory($riskValue, $matrix = '3x3')
{
    $category = NULL;
    $color = 'fcfcff';
    switch ($matrix) {
        case '5x5':
            if ($riskValue >= 12) {
                $category = 'High';
                $color = 'DD2C2C';
            }
            if ($riskValue >= 5 && $riskValue <= 11) {
                $category = 'Medium';
                $color = 'FFA500';
            }
            if ($riskValue >= 1 && $riskValue <= 4) {
                $category = 'Low';
                $color = '3BD135';
            }
            break;

        default:
            if ($riskValue >= 6) {
                $category = 'High';
                $color = 'DD2C2C';
            }
            if ($riskValue >= 3 && $riskValue <= 5) {
                $category = 'Medium';
                $color = 'FFA500';
            }
            if ($riskValue >= 1 && $riskValue <= 2) {
                $category = 'Low';
                $color = '3BD135';
            }
            break;
    }
    return array($category, $color);
}
function riskImpactMatrix()
{
    $impact_matrices = [
        '3x3' => [
            ['value' => 1, 'name' => 'Minor'],
            ['value' => 2, 'name' => 'Moderate'],
            ['value' => 3, 'name' => 'High'],
        ],
        '5x5' => [
            ['value' => 1, 'name' => 'Negligible'],
            ['value' => 2, 'name' => 'Slight'],
            ['value' => 3, 'name' => 'Moderate'],
            ['value' => 4, 'name' => 'High'],
            ['value' => 5, 'name' => 'Very High'],
        ]
    ];
    return $impact_matrices;
}
function riskLikelihoodMatrix()
{
    $likelihood_matrices = [
        '3x3' => [
            ['value' => 1, 'name' => 'Unlikely', 'summary' => 'It probably would not occur'],
            ['value' => 2, 'name' => 'Possible', 'summary' => "There is a possibility that it could happen"],
            ['value' => 3, 'name' => 'Likely', 'summary' => 'The risk is more likely to happen than not'],
        ],
        '5x5' => [
            ['value' => 1, 'name' => 'Rare', 'summary' => 'Has never happened before and there is no reason to think it is any more likely now'],
            ['value' => 2, 'name' => 'Unlikely', 'summary' => "There is a possibility that it could happen, but it probably won't"],
            ['value' => 3, 'name' => 'Possible', 'summary' => 'On balance, the risk is more likely to happen than not'],
            ['value' => 4, 'name' => 'Very Likely', 'summary' => 'It would be a surprise if the risk did not occur either based on past frequency or current circumstances'],
            ['value' => 5, 'name' => 'Almost  Certain', 'summary' => 'Either already happens regularly or there are some reasons to believe it is virtually imminent'],
        ]
    ];
    return $likelihood_matrices;
}
function defaultImpactCriteria()
{
    return [
        // 'Confidentiality',
        // 'Integrity',
        // 'Availability',
        // 'Privacy',
        'Financial Impact',
        'Health & Safety Impact',
        'Reputational Impact',
        'Legal Impact',
        'Stakeholders Impact',
        'People Impact',
        'Operational Impact',
        'Regulatory Impact'
    ];
}
function defaultBiaTimeRecoveryRequirement()
{
    return [
        ['time_in_minutes' => 60, 'name' => 'Less than 1 Hour'],
        ['time_in_minutes' => 180, 'name' => '3 Hours'],
        ['time_in_minutes' => 540, 'name' => '1 Day'],
        ['time_in_minutes' => 1620, 'name' => '3 Days'],
        ['time_in_minutes' => 2700, 'name' => '1 Week'],
        ['time_in_minutes' => 5400, 'name' => '2 Weeks'],
    ];
}
function scoreInPercentage($numerator, $denominator)
{
    if ($denominator > 0) {
        return ($numerator / $denominator) * 100;
    }
    return 0;
}
function rainbowColor($color)
{
    $arr = [
        "1" => "bg-blue",
        "2" => "bg-orange",
        "3" => "bg-green",
        "4" => "bg-red",
        "5" => "bg-yellow",
        "6" => "bg-brown",
        "7" => "bg-pink",

    ];

    if ($color) {
        return $arr[$color];
    }
    return $arr;
}


/**
 * Delete Message
 * @return String
 */
function formatToTwoDecimalPlaces($gpa)
{
    return sprintf("%01.2f", $gpa);
}

function deleteMessage()
{
    return 'yes';
}
/**
 * @param null $status
 * @return array|mixed
 */
function status($status = null)
{
    $arr = [
        0 => 'De-active',
        1 => 'Active'
    ];
    if ($status !== null) {
        return $arr[$status];
    }
    return $arr;
}


function countries()
{
    return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
}

function defaultPasswordStatus()
{
    return 'default';
}

function todayDateTime()
{
    return date('Y-m-d H:i:s', strtotime('now'));
}

function todayDate()
{
    return date('Y-m-d', strtotime('now'));
}

function getDateFormat($dateTime)
{
    return date('Y-m-d', strtotime($dateTime));
}

function getDateFormatWords($dateTime)
{
    return date('l M d, Y', strtotime($dateTime));
}

function fromDate()
{
    return date('Y-m-d' . ' 07:30:00', time());
}
function generateNumber($next_no)
{
    $no_of_digits = 4;

    $digit_of_next_no = strlen($next_no);
    $unused_digit = $no_of_digits - $digit_of_next_no;
    $zeros = '';
    for ($i = 1; $i <= $unused_digit; $i++) {
        $zeros .= '0';
    }
    return $zeros . $next_no;
}
function toDate()
{
    return date('Y-m-d' . ' 16:00:00', time());
}
function convertPercentToUnitScore($factor, $numerator, $denominator = 100)
{
    $converted_score = $numerator / $denominator * $factor;
    return sprintf("%01.1f", $converted_score);
}
function deleteSingleElementFromString($parent_string, $child_string)
{
    $string_array = explode('~', $parent_string);

    $count_array = count($string_array);

    for ($i = 0; $i < ($count_array); $i++) {

        if ($string_array[$i] == $child_string) {

            unset($string_array[$i]);
        }
    }
    return implode('~', array_unique($string_array));
}
function addSingleElementToString($parent_string, $child_string)
{
    if ($parent_string == '') {
        $str = $child_string;
    } else {
        $str = $parent_string . '~' . $child_string;
    }


    $string_array = array_unique(explode('~', $str));

    return implode('~', $string_array);
}

function randomColorCode()
{
    $tokens = 'ABC0123456789'; //'ABCDEF0123456789';
    $serial = '';
    for ($i = 0; $i < 6; $i++) {
        $serial .= $tokens[mt_rand(0, strlen($tokens) - 1)];
    }
    return '#' . $serial;
}
function randomPassword()
{
    $tokens = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ23456789!@#$%&*{}[]';
    $serial = '';
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 4; $j++) {
            $serial .= $tokens[mt_rand(0, strlen($tokens) - 1)];
        }
        // if ($i < 2) {
        //     $serial .= '-';
        // }
    }
    return $serial;
}
function randomNumber($loop = 6)
{
    $tokens = '0123456789';
    $serial = '';
    for ($j = 0; $j < $loop; $j++) {
        $serial .= $tokens[mt_rand(0, strlen($tokens) - 1)];
    }
    return $serial;
}
function randomcode()
{
    $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789';
    $serial = '';
    for ($i = 0; $i < 2; $i++) {
        for ($j = 0; $j < 4; $j++) {
            $serial .= $tokens[mt_rand(0, strlen($tokens) - 1)];
        }
        if ($i < 1) {
            $serial .= '-';
        }
    }
    return $serial;
}

function schoolDays($day = null)
{
    $arr = [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday'

    ];

    if ($day) {
        return $arr[$day];
    }
    return $arr;
}

function schoolDaysStr($day = null)
{
    $arr = [
        'Monday' => '1',
        'Tuesday' => '2',
        'Wednesday' => '3',
        'Thursday' => '4',
        'Friday' => '5'

    ];

    if ($day) {
        return $arr[$day];
    }
    return $arr;
}

/**
 *This ranks student based on score
 *@param $score = the score you want to rank
 *@param $scores = array of sorted scores from top to least
 *@return $position = position of the score
 */
function rankResult($score, $scores)
{
    rsort($scores);
    $position = "";
    foreach ($scores as $key => $each_score) {
        //$position = "";
        if ($score == $each_score) {
            $position = array_search($score, $scores) + 1;
            break;
        }
    }
    if ($position == '1' || (strlen($position) == '2' && substr($position, 0, 1) != '1') && substr($position, 1) == '1') {
        $position = $position . 'st';
    } else if ($position == '2' || (strlen($position) == '2' && substr($position, 0, 1) != '1') && substr($position, 1) == '2') {
        $position = $position . 'nd';
    } else if ($position == '3' || (strlen($position) == '2' && substr($position, 0, 1) != '1') && substr($position, 1) == '3') {
        $position = $position . 'rd';
    } else {
        $position = $position . 'th';
    }
    return $position;
}

function nationalExpectationDescription()
{
    $arr = [
        'WT' => 'Working Towards National Expectation',
        'WE' => 'Working as Expected',
        'WA' => 'Working Above Expectation',
    ];
    return $arr;
}

function nationalExpectationKey()
{
    $arr = [
        '0' => ['Score %', '0 - 49', '50 - 84', '85 - 100'],
        '1' => ['National Expectation', 'WT', 'WE', 'WA'],
        '2' => ['Grade', 'C', 'B', 'A'],
        '3' => ['Attainment', 'Developing', 'Secure', 'Outstanding'],
    ];
    return $arr;
}

function nationalExpectationGrade($grade)
{
    if ($grade >= 85 && $grade <= 100) {
        $attainment = ['A', 'Outstanding', 'WA'];
    } else if ($grade >= 50 && $grade <= 84) {
        $attainment = ['B', 'Secure', 'WE'];
    } else {
        $attainment = ['C', 'Developing', 'WT'];
    }

    return $attainment;
}
function registrationPinType($type = null)
{
    $arr = [
        'teacher' => 'Teacher',
        'student' => 'Student',


    ];

    if ($type) {
        return $arr[$type];
    }
    return $arr;
}
function disabilities()
{

    return [
        "NA" => "Not Applicable",
        "Eye Defect" => "Eye Defect",
        "Ear Defect" => "Ear Defect",
        "Dumb" => "Dumb",
        "Paralyzed" => "Paralyzed"


    ];
}
function gender($gender = null)
{
    $arr = [
        'Male' => 'Male',
        'Female' => 'Female',


    ];

    if ($gender) {
        return $arr[$gender];
    }
    return $arr;
}

function sections($name = null)
{
    $arr = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'E' => 'E',
        'F' => 'F'



    ];

    if ($name) {
        return $arr[$name];
    }
    return $arr;
}

function occupation()
{
    $arr = [
        'Civil Servant' => 'Civil Servant',
        'Public Servant' => 'Public Servant',
        'Professional' => [
            'Architect' => 'Architect',
            'Banker' => 'Banker',
            'Doctor' => 'Doctor',
            'Engineer' => 'Engineer',
            'Nurse' => 'Nurse',
            'Lawyer' => 'Lawyer',
            'Teacher' => 'Teacher'
        ],
        'Business Person' => 'Business Person',
        'Others' => 'Others (if not listed here)',


    ];

    return $arr;
}

function hashing($string)
{
    $hash = hash('sha512', $string);
    return $hash;
}

function formatUniqNo($no)
{
    $no = $no * 1;
    if ($no < 10) {
        return '000' . $no;
    } else if ($no >= 10 && $no < 100) {
        return '00' . $no;
    } else if ($no >= 100 && $no < 1000) {
        return '0' . $no;
    } else {
        return $no;
    }
}
function mainDomainPublicPath($folder = null)
{
    return "https://decompass.com/" . $folder;
}
function subdomainPublicPath($folder = null)
{
    return "/var/www/decompass.com/storage/" . $folder;
}

function portalPulicPath($folder = null)
{
    if (env('PLATFORM') == 'local') {

        return storage_path('app/public/' . $folder);
    }
    return "/var/www/decompass.com/storage/" . $folder;
}

function folderSize($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }

    // this size is in Byte
    // we want to convert it to GB
    // 1Gb = 1024 ^ 3 Bytes OR 1Gb = 2 ^ 30

    return $size;
    // return sizeFilter($size); //byteToGB($size);
}

function byteToGB($byte)
{
    $gb = $byte / 1024 / 1024 / 1024;
    return $gb;
}

function percentageDirUsage($dir_size, $total_usable)
{
    $used = $dir_size / $total_usable * 100;
    return (float) sprintf('%01.2f', $used);
}
function folderSizeFilter($bytes)
{
    $label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

    for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++)
        ;

    return (round($bytes, 2) . $label[$i]);
}

function getDatabaseName($connection, $includePeriod = true)
{
    if (empty(config('database.connections.' . $connection . '.database'))) {
        new Exception('no database connection for' . $connection);
    }

    if ($includePeriod === false) {
        return config('database.connections.' . $connection . '.database');
    }

    return config('database.connections.' . $connection . '.database') . '.';
}