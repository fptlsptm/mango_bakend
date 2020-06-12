
<style type="text/css">
table{border-collapse: collapse;}
th {font-weight:bold;padding:5px; min-width:120px; width:120px;text-align:center; line-height:18px; font-size:12px; color:#959595; font-family:dotum,돋움; border-right:1px solid #e4e4e4;}
td {text-align:center; line-height:40px; font-size:12px; color:#474747; font-family:gulim,굴림; border:1px solid #e4e4e4;}
</style>
<?
header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = mangoDataList.xls" );
header( "Content-Description: PHP4 Generated Data" );

$q10 = ["자궁경부의 염증성질환(N72)","자궁내막증(N80)","무월경, 소량, 희발월경(N91)","과다, 빈발 및 불규칙 월경(N92)","기타 이상 자궁 및 질 출혈(N93)",
            "여성 생식기관 및 월경주기와 관련된 통증 및 기타 병태(N94)","자궁의 평활근종(D25)","난소의 기능이상(E28)","기타 및 상세불명의 난소낭(N38.2)",
            "자궁경부의 악성 신생물(C53)","자궁체부의 악성 신생물(C54)","기타 여성 골반염증질환(N73)","부위가 명시되지 않은 요로감염(N39.0)",
            "상세불명의 요실금(R32)","없음","기타(기입)"];
$q12 = ["좋은느낌(패드형)","화이트(패드형)","위스퍼(패드형)","릴리안(패드형)","순수한면(패드형)","소피 바디피트(패드형)",
          "소피 귀애랑(패드형)","시크릿데이(패드형)","예지미인(패드형)","유기농본(패드형)","라엘(패드형)","청담소녀(패드형)",
          "내츄럴코튼(패드형)","라라문(패드형)","플레이텍스(패드형)","라네이처(패드형)","좋은느낌(삽입형)","화이트(삽입형)",
          "플레이텍스(삽입형)","탐팩스(삽입형)","순수한면(삽입형)","템포(삽입형)","기타(기입)"];

$q14 = ["감정기복","피로감","불안감","복통","슬픔","수면 패턴의 변화",
          "식욕 약화","식욕 강화","집중력 저하","지속적인 짜증","설사","구토","유방통",
          "수족냉증","가슴 두근거림","오한","변비","숨 가쁨","소화장애","기절","건망증","불면증","없음","기타(기입)"];

$q17 = ["따뜻한 음료 마시기","휴식","온찜질","마사지","운동","약 복용","병원진료","안한다","기타(기입)"];

$q18 = ["이지엔6 이브","이지엔6 애니","이지엔6 프로","이지엔6 스트롱","그날엔Q 삼중정","그날엔 정","펜잘","펜잘레이디","멘자펜","게보린",
            "캐롤에프","애드빌 리퀴겔","탁센","탁센이브","타이레놀정","우먼스 타이레놀","타이레놀ER 서방정","트리스펜","덱쎈","덱시부펜",
            "낙센","없음"];

$q19 = ["야스민","야즈","클래라","에이리스","라니아","애니브","미니보라","센스리베","센스데이","머시론",
            "디어미","멜리안","마이보라","보니타","엘라원","포스티노","레보니아 원 정","없음"];

$q22 = ["지끈지끈하다","깨질 것같다","욱신거리다","어지럽다","없음"];

$q24 = ["거북하다","답답하다","땡기다","묵직하다","바늘로 쑤시듯하다","살살 아프다","쥐어짜는 듯하다","찌릿찌릿하다","저리다","화끈하다",
            "둔하다","터질 듯이 아프다","없음"];

$q26 = ["사르르하다","싸하다","얼얼하다","거북하다","더부룩하다","답답하다","뒤틀리다","떙기다","묵직하다","바늘로 쑤시듯하다",
            "보글보글 끓는다","살살 아프다","쓰리다","잡아당기다","조이는 듯하다","쥐어짜는 듯하다","가스가 차서 팽창하는 듯하다","칼로 도려내는 듯하다","욱신거리다",
            "경련하는 듯하다","메스껍다","없음"];

$q28 = ["뜨끔뜨끔하다","부서지는 듯하다","뻐근하다","쑤시다","시리다","아리다","짓누르다","찌릿찌릿하다","화끈하다","저리다",
            "욱신거리다","둔하다","없음"];

$q30 = ["땡기다","묵지근하다","시리다","쑤시다","아리다","얼얼하다","저리다","조이는 듯하다","뻐근하다","욱신거리다",
          "둔하다","없음"];

$q32 = ["따끔거리다","땡기다","묵지근하다","시리다","쑤시다","아리다","얼얼하다","저리다","조이는 듯하다","욱신거리다",
          "뻐근하다","붓다","둔하다","없음"];

$q34 = ["가렵다","맵다","따갑다","따끔하다","쓰라리다","아리다","얼얼하다","후비다","불에 타는 듯하다","밑이 빠지는 듯하다",
            "없음"];

$q36 = ["우울하다","나를해치고싶다"," 남을해치고싶다","혼자있고싶다","분노조절이안된다","울고싶다","토하고싶다",
            "집중이 안된다","불안하다","짜증난다","무기력하다","예민하다","기분이 좋다","없음"];
            
$q38 = ["없음","우울하다","나를해치고싶다"," 남을해치고싶다","혼자있고싶다","분노조절이안된다","울고싶다","토하고싶다",
                "집중이 안된다","불안하다","짜증난다","무기력하다","예민하다","기분이 좋다"];

$th_array = ['회원id','설문일','키',"몸무게",'마지막생리일','평균생리기간','평균생리주기','직업군','혼인여부',
            '출산경험','초경시작나이','산부인과 병명','생리통 정도(vas, 1~10)','생리통 빈도','한 번의 생리기간 중 불편함을 느끼는 기간은?',
            '생리 중 증상을 모두 선택해주세요.','생리정보 출처	주 의료기관','생리 중 생리통 완화를 위해서 케어하는 방법이 있다면 무엇인가요',
            '생리 중 생리통 완화를 위해서 케어하는 방법이 있다면 무엇인가요?',
            '복용경험이 있는 진통제약을 기억나는대로 모두 선택해주세요. (없음 포함)',
            '복용경험이 있는 경구피임약을 기억나는대로 모두 선택해주세요. (없음 포함)',
            '약복용효과','머리통증정도','머리 부위의 통증을 표현하는 언어로 알맞은 것은 무엇입니까?',
            '가슴통증정도','가슴 부위의 통증을 표현하는 언어로 알맞은 것은 무엇입니까?',
            '배통증정도','배 부위의 통증을 표현하는 언어로 알맞은 것은 무엇입니까',
            '허리통증정도','허리 부위의 통증을 표현하는 언어로 알맞은 것은 무엇입니까?',
            '골반통증정도','골반 부위의 통증을 표현하는 언어로 알맞은 것은 무엇입니까?',
            '관절통증정도','관절 부위의 통증을 표현하는 언어로 알맞은 것은 무엇입니까?',
            '생식기통증정도','생식기 부위의 통증을 표현하는 언어로 알맞은 것은 무엇입니까?',
            '심리적통증정도','심리적 통증을 표현하는 언어로 알맞은 것은 무엇입니까?',
            '생리 중 기분을 점수로 환산한다면?','생리 중 아무도 모르는 내 기분을 어떻게 표현할 수 있을까요?'];
$td_array = ['mem_userid','ch_date'];

for ($i=1; $i <39 ; $i++) {
  $td_array[] = "q".$i;
}
// colspan='2' 병합

$EXCEL_STR = "<table>";
// $EXCEL_STR  .= "<tr>";
// for ($i=0; $i <count($th_array) ; $i++) {
//     $colspan = 1;
//     if(is_array(${$td_array[$i]})){
//       $colspan = count(${$td_array[$i]});
//     };
//     $EXCEL_STR .= "<th colspan='{$colspan}'>".$th_array[$i]."</th>";
// }
// $EXCEL_STR  .= "</tr>";

$EXCEL_STR .= "<tr>";
for ($i=0; $i <count($th_array) ; $i++) {
    $qArr = ${$td_array[$i]};
    $td = str_replace("q","a",$td_array[$i]);
    $td = str_replace("mem_userid","회원아이디",$td);
    $td = str_replace("ch_date","설문일",$td);
    if(is_array($qArr)){
      for ($j=1; $j < count($qArr)+1; $j++) {
        $EXCEL_STR .= "<th>{$td}{$j}</th>";
      }
    }else {
      $EXCEL_STR .= "<th>{$td}</th>";
    };
}
$EXCEL_STR  .= "</tr>";
//위에 talbe은 자신이 가져올 값들의 컬럼 명이 되겠다.
foreach ($list as $val){
   $EXCEL_STR .= "<tr>";
   for ($i=0; $i <count($th_array) ; $i++) {
       $qArr = ${$td_array[$i]};
       if(is_array($qArr)){
         for ($j=0; $j < count($qArr); $j++) {
           $value = 0;
           if(strpos($val[$td_array[$i]], $qArr[$j]) !== false){
             $value =1;
           }
           $EXCEL_STR .= "<td>{$value}</td>";
         }
       }else {
          $EXCEL_STR .= "<td style=mso-number-format:'\@'>".$val[$td_array[$i]]."</td>";
       };
   }
   $EXCEL_STR .= "</tr>";
}
$EXCEL_STR .= "</table>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'> ";
echo $EXCEL_STR;

?>
