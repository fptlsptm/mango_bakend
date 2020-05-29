<style>
  .box{width:1100px;}

  .cus_table{width:100%;font-size:14px;margin-bottom: 20px;;}
  .cus_table td,.cus_table th{border:1px solid #ddd;padding:10px;}
  .cus_table th{background-color:#eee;color:#404040;width:100px;}
</style>
<div class="box">
  <h3>회원 상세정보</h3>
  <table class="cus_table">
    <tr>
      <th>이메일</th>
      <td><?=$mem['mem_email']?></td>
      <th>포인트</th>
      <td><?=$mem['mem_point']?>P</td>
      <th>생년월일</th>
      <td><?=$mem['mem_birthday']?></td>
    </tr>
    <tr>
      <th>키</th>
      <td><?=$ch['q1']?>cm</td>
      <th>몸무게</th>
      <td><?=$ch['q2']?>kg</td>
      <th>최근생리일</th>
      <td><?=$ch['q3']?></td>
    </tr>
    <tr>
      <th>평균생리기간</th>
      <td><?=$ch['q4']?>일</td>
      <th>평균생리주기</th>
      <td><?=$ch['q5']?>일</td>
      <th>직업군</th>
      <td><?=$ch['q6']?></td>
    </tr>
    <tr>
      <th>혼인여부</th>
      <td><?=$ch['q7']?>일</td>
      <th>출산경험</th>
      <td><?=$ch['q8']?>일</td>
      <th>초경시작나이</th>
      <td><?=$ch['q9']?>살</td>
    </tr>
  </table>
  <h3>증상 상세정보</h3>
  <table class="cus_table">
    <tr>
      <th>일자</th>
      <th>현재증상</th>
      <th>복용진통제</th>
      <th>심리적양상</th>
      <th>머리</th>
      <th>가슴</th>
      <th>배통증</th>
      <th>허리통증</th>
      <th>골반통증</th>
    </tr>
    <?foreach ($mc as $key => $val) {?>
      <tr>
        <td><?=$val['mc_date']?></td>
        <td><?=$val['q22']?></td>
        <td><?=$val['q18']?></td>
        <td><?=$val['q36']?></td>
        <td><?=$val['q21']?></td>
        <td><?=$val['q23']?></td>
        <td><?=$val['q25']?></td>
        <td><?=$val['q27']?></td>
        <td><?=$val['q29']?></td>
      </tr>
    <?}?>
  </table>
</div>
