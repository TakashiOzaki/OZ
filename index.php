<!DOCTYPE html>

<!-- 
○宿題事項
　htmlファイルを一人一本作る
　(使うタグ：html、head、body、h1、ul、li、br、div、p、a
 　id属性、class属性を付けてみる、そして！cssで表示を変えてみる) 
-->

<html>
<head>
  <meta charset="utf-8" />
  <title>宿題(尾崎)</title>
  
  <link rel="stylesheet" href="test.css" type="text/css">
</head>

<body>
<h1>ここからボディ</h1>
<h1>ここからPHPで記述してみる</h1>
<?php
 //変数の定義は$で行う。型は自動で変換される
 $value = '$valueの中身を表示しています';
 //echoで出力、シングルコートでもダブルコートでも。
 echo "<h1>hello world</h1>\n";
 //ただしダブルは変数やコードは解釈できるけど、シングルはそのまま表示される
 //たとえば、改行は\nだけど、ダブルでは改行されるが
 //シングルでは\nという文字列が表示される
 echo '$value\n';
 echo "$value\n";
 //文字列の結合は「.」で
 echo '<h1>'.$value.'</h>';
 echo "<h1>$value</h>";
 
 echo "<h1>0-10の範囲でランダムに5つ表示しています</h1>\n";
 //乱数を使った方法
 $arraySelectFlg = \array_fill(0, 10, FALSE);//各数字について選択されたか管理
 $selectCnt = 0;                            //選択された回数
 $randNum;                                  //乱数で生成された数字
 while ($selectCnt <= 5) {
    $randNum = mt_rand(0,10);        
    IF($arraySelectFlg[$randNum] == FALSE){
        echo  $randNum."<br>";
        $arraySelectFlg[$randNum] = TRUE;
        $selectCnt++;
     }            
 }
 //配列ランダムに並べ替えた後に先頭5つの選択
 $array0_10 = range(0,10);
 shuffle($array0_10);
 For($i = 0 ; $i < 5 ;$i++){
    echo $array0_10[$i]."<br>"; 
 }
 
 
 
?>
  <ul>
<?php
//偶数だけリスト表示してみます。

//配列の初期化はarray()
$arr = array();

//For文で繰り返し
For($i = 0 ; $i <= 10 ;$i++){
    //if文で条件文
    IF ($i % 2 == 0){
        //[]で要素数を指定しないと勝手に後ろに追加される
        $arr[] = "<li>リスト$i</li>";
    }
}
//count()で配列の要素数を返却する
For($i = 0 ; $i < count($arr); $i++){
    echo "$arr[$i]\n";
}

?>
  </ul>
<h1>h1：見出し1</h1>
<h2 class="h1">p:Paragraphの略囲まれた部分がひとつの段落であることを表します</h2>
<p id="p1">例：こんな感じ</p>
<p id="p2">例：こんな感じ</p>

<h2 class="c1">div：それ自身は特に意味を持っていませんが、囲んだ範囲をひとかたまりとして、 align属性で位置を指定したり、スタイルシートを適用するのに用います</h2>
<div>例：こんな感じ</div>

<h2 class="h2">ul:Unordered Listの略で、順序のないリストを表示する際に使用します</h2>
<h2 class="h2">type属性により、 黒丸（disc）、白丸（circle）、黒い四角（square）を指定することができます</h2>
<h3>ul:Unordered Listの略で、順序のないリストを表示する際に使用します</h3>
  <ul>
    <li>例：</li>
    <li>指定なし</li>
  </ul>
  <ul type="circle">
 
   <li>白丸</li>
  </ul>
  <ul type="disc">
    <li>黒丸</li>
  </ul>
  <ul type="square">
    <li>黒い四角</li>
  </ul>

<h2>改行はbrタグで指定します</h2>
例：改<BR>行

<h2>最後にAタグ</h2>

<a href="index.html">このページに再ジャンプ</a>
</body>
</html>
