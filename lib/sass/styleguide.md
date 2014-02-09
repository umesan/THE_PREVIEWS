# 制作ガイドライン


バージョン：0.1  
最終更新日：2014/02/01


## はじめに
THE PREVIEW 制作ガイドラインです。  
こちらのルールに従って制作しています。


## HTML


### ドキュメントタイプ <em class="REQ">※必須</em>
HTML5

<pre class="brush:html">
&lt;!DOCTYPE html&gt;
&lt;html lang="ja"&gt;
</pre>


### エンコード <em class="REQ">※必須</em>
UTF-8 （BOM無し）
<pre class="brush:html">
<meta charset="utf-8">
</pre>


### インデント <em class="REQ">※必須</em>
タブ(半角4つぶん)を使用。


### 改行コード <em class="REQ">※必須</em>
CR+LF


### 閉じタグについて <em class="REQ">※必須</em>
終了タグを省略して表記。

<pre class="brush:html">
<meta charset="shift_jis">
<br>
&lt;img src="xxx.png" alt="xxxx" width="100" height="100"&gt;
</pre>


### type属性の省略 <em class="REQ">※必須</em>
link や script タグの type属性を省略して表記。

<pre class="brush:html">
&lt;script src="xxxx.js"&gt;&lt;/script&gt;
&lt;link rel="stylesheet" href="xxx.css"&gt;
</pre>


### 実態参照について <em class="REQ">※必須</em>
特殊文字は実態参照で表記。

<pre class="brush:html">
&amp;  →  &amp;amp;
&copy;  →  &amp;copy;
&lt;  →  &amp;lt;
&gt;  →  &amp;gt;
&#12316; →  &amp;#12316;
</pre>


＜参考サイト＞
> 実態参照一覧  
> http://www.ne.jp/asahi/minazuki/bakera/html/reference/charref

### コメント
第三者が内容を把握しやすくするため、適宜コメントを記述。  


### BEM

BEMのルールでコーディング。

> ＜BEMについて＞  
> https://github.com/juno/bem-methodology-ja/blob/master/definitions.md
>
> ＜実践 めんどうくさくない BEM＞  
> http://tsmd.hateblo.jp/entry/2013/12/12/004059
>
> ＜MindBEMding＞  
> http://csswizardry.com/2013/01/mindbemding-getting-your-head-round-bem-syntax/
>
> ＜BEM とは＞  
> http://chroma.hatenablog.com/entry/2013/12/12/200817


## CSS ＆ Sass ガイドライン

### 概要

CSS・Sassの品質を保つため、CSS, Sass 定義時のルールを設ける。  
CSSとSassで共通となるルールを定義している。  

CSS・Sassそれぞれのルールに関しては、  
別途「CSSガイドライン」「Sassガイドライン」にて解説する。


### 優先順位

CSS・Sassは下記の優先順位に沿って設計。  
新規スタイルの際に迷ったり、既存コードについて疑問に思った際は、  
下記の優先順位を確認。

> 1. 更新時に、スタイルのコンフリクトによる事故を起こさないこと
> 2. スタイルファイルサイズが軽量であること
> 3. 制作時にclass名で悩まないこと
> 4. スタイルの拡張・修正がしやすいこと
> 5. 複数人で同時作業が可能なこと
> 6. 誰でも更新できること（制作者のSass・CSSの理解度に依存しない）


### Sassの活用 <em class="REQ">※必須</em>

THE PREVIEW では、Sassを使ってCSSコーディングの効率化する。  
そのため、Sass環境を構築していない場合は、スタイル編集・制作を原則禁止。  

<div class="RED">
絶対に、cssファイルを直接編集しない。
</div>


### 文字コード <em class="REQ">※必須</em>
CSS・Sassともに、**UTF-8** を採用し、各CSSファイルの先頭で文字コード宣言を行う。
<pre class="brush:css">
@charset "UTF-8";
</pre>


### 改行コード <em class="REQ">※必須</em>
Css・Sassともに、CR+LFを採用


### インデント <em class="REQ">※必須</em>

＜CSS＞  
開発時は、半角スペース二つ  
本番配信用ファイルでは、圧縮するためインデントなし
　  
　  
＜Sass＞  
タブ（半角4つ分）

<div class="YEL">
Sassのコンパイル設定によって、出力されるCSSファイル内のインデントは変わります。  
　  
Sassのデフォルト設定では、  
コンパイル設定をexpandedやnested形式にした場合、  
半角2つでインデントがとられます。  
　  
Sassファイル内では、インデントをタブ（半角4つ分）でとっていますが、  
出力されるCSSは、上記デフォルト設定に任せています。  
　  
同一プロジェクト内において、  
インデント形式の異なるドキュメントファイルが存在するという事実は、  
誠に遺憾ではありますが、以下の理由により許容しています。  
　  
1. 最終的にcompressedするためインデントはなくなる  
2. このプロジェクトではCSSファイルを直接編集することはない  
　  
　  
尚、どうしても我慢ならぬ方は、  
下記を参考にコンパイルされるCSSファイルのインデント形式を変更してください。  
http://www.skyward-design.net/blog/archives/000121.html

</div>


### IDセレクタへのスタイル指定禁止 <em class="REQ">※必須</em>
**IDセレクタへのスタイルはつけない。**  
スタイルはclass に対して指定すること。  

＜理由＞  
> * IDセレクタに付加したスタイルは再利用ができないため  
> * マルチクラスによる、オーバーライドがしにくくなるため  
> * オーバーライドするために、セレクタを増やすことになるため、結果ファイルサイズが増加する  


### js- プレフィックスを付けたクラスへのスタイル指定禁止
JavaScript用のプレフィックスクラスである js- に対してはスタイルは付加しません。


### h1,h2,h3,h4,h5,h6・・・タグへのスタイル指定禁止
h1,h2,h3等の見出しタグへの、共通CSSでスタイル付けをおこなわない。<br>
スタイルを付加したい場合は、別途スタイル用のクラス（例、class="m-tit"）を割り当ててください。

＜理由＞
> h1 や h2 にスタイルを直接付加してしまうと、スタイルに左右されてしまい、決まったエリアにしかh1,h2が使えなくなります。  
> サイトの構成上用途に応じた適切な箇所にタグ付けするために、h1 や h2 にスタイルを直接付加しません。


### プロパティの値が0の場合は単位を省略する

プロパティの値として 0 を指定する場合は、単位表示を省略する

＜採用＞
<pre class="brush:css">
margin: 0;
</pre>

＜非採用＞
<pre class="brush:css">
margin: 0px;
</pre>


### ゼロ以下の値を記述する場合は、0を含める

ゼロ以下の値（0.x）を指定する場合、見通しを考慮して、0は明記する。  
一瞬間違っているかと思うため。

＜採用＞
<pre class="brush:css">
font-size: 0.9em;
</pre>

＜非採用＞
<pre class="brush:css">
font-size: .9em;
</pre>


### 色定義

色定義は、16進数で定義し、6桁で記述する。  
表記統一のため。

＜採用＞
<pre class="brush:css">
color: #ff0000;
</pre>

＜非採用＞
<pre class="brush:css">
color: #f00;
color: red;
</pre>


### CSS内の url() 指定に関して
url() ではシングルクォーテーションを使用。  
クォーテーションなしや、ダブルクォーテーションは利用しない。  
CompassのHelper関数利用時も同様。  

理由は、表記統一のため（Compassのデフォルト出力がシングルクォーテーション）。

＜採用＞
<pre class="brush:css">
background-image: url('../img/test.png');
// CompassのHelper関数利用時
background-image: image-url('../img/test.png');
</pre>

＜非採用＞
<pre class="brush:css">
background-image: url(../img/test.png);
background-image: url("../img/test.png");
</pre>


### コメントルール

開発中のコードの見通しをよくするために、各セクションに対して適宜コメントを記述。  
最終的にcssをminify化する際にコメントを削除。


#### 目次
<pre class="brush:css">
/\*\*
 \* PROJECT NAME
 \* @require 必要な環境、フレームワーク類があれば記述
 \* @version x.x.x
 \* @update xxxx/xx/xx
 \*/
</pre>

#### 大見出し
<pre class="brush:css">
/\*----------------------------------------

 大見出し

----------------------------------------\*/
</pre>


#### 中見出し
<pre class="brush:css">
/\*----------------------------------------
 中見出し
----------------------------------------\*/
</pre>


#### 小見出し
<pre class="brush:css">
/\* 小見出し \*/
</pre>



### !important の使用について
ヘルパークラスに使うのは可。  
場当たり的な問題解決として利用するのは禁止。

<pre class="brush:css">
.error{ color: red !important; }
</pre>


### セレクタとプロパティの記述ルール

<div class="YEL">
THE PREVIEWではSassを使っているため、開発環境では自動的に下記の形式でCSSが出力されます。  
本番配信時は、CSSファイルが圧縮されるため上記のルールには当てはまらなくなりますが問題ありません。
</div>

スタイルを記述する際は、下記の点に注意すること。

> * セレクタの直後には半角スペースを記述
> * プロパティの前にはタブ1つでインデント
> * 指定が複数になる場合は基本1行に1プロパティ
> * 指定が少ない場合は1行で記述してもよい
> * セミコロンは省略せず必ず付ける

#### 複数行の場合

<pre class="brush:css">
.selector {
    property1: value1;
    property2: value2;
}
</pre>

#### 1行の場合
<pre class="brush:css">.selector { property3: value3; }</pre>


### DOMの構成と一致するようにルールセットのインデント

DOMの構成と一致するようにCSS側もルールセットをインデントすると、  
親子関係が把握できてコードの見通しが良くなるため、  
開発時には、こちらのルールを推奨（強制はしない）。

#### HTML
<pre class="brush:html">
<div class="m-header">
  <p class="header-link"></p>
</div>
</pre>

#### CSS
<pre class="brush:css">
.m-header {
    property: value;
}
    .header-link {
        property: value;
    }
</pre>


## CSSガイドライン

### 概要

CSSの品質を保つため、CSS定義時のルールを設ける。  
基本的にこちらのルールを遵守。

<div class="YEL">
THE PREVIEW では、Sassを利用しているため、  
このCSSガイドラインは、「SassからコンパイルされたCSSファイルが、どういう状態にあるべきか」 を定義する。  
</div>



### CSSファイルの外部ファイル化
CSSファイルは外部ファイル化すること  

<pre class="brush:css">
<link rel="stylesheet" href="xxx.css" media="all">
</pre>


### HTML内、インラインスタイルの禁止
htmlファイルのhead要素内での指定は不可。  

<pre class="brush:css">
&lt;html lang="ja"&gt;
&lt;head&gt;
<meta charset="utf-8">
<title>head内でのスタイル指定は禁止</title>
<style>
.ban{ display: none; }
</style>
&lt;/head&gt;
</pre>


htmlファイルに、インラインでのstyle属性での指定も不可

<pre class="brush:css">
<p style="display: block;">インラインでのスタイル指定禁止</p>
</pre>


### 全体構成

1ファイルにまとめて記述する方式を採用。  
ページ毎のスタイルファイルは持たない。  
スタイルシートの大まかな構成は下記の通り。

> 1. リセットCSS
> 2. サイト独自の初期設定
> 3. サイト独自の汎用（ヘルパー）クラス
> 4. 大枠レイアウト情報
> 5. 個別モジュール


### リセットCSS <em class="REQ">※必須</em>

THE PREVIEWのリセットCSSは、html5doctor.com Reset Stylesheet v1.6.1 を使用しています。  
リセットCSSではリセットしきれないスタイルや、サイト制作時に役立つ汎用クラス等は  
リセットCSSのあとに追記しています。

<pre class="brush:css">
/\*
html5doctor.com Reset Stylesheet v1.6.1
Last Updated: 2010-09-17
Author: Richard Clark - http://richclarkdesign.com
\*/
html, body, div, span, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
abbr, address, cite, code,
del, dfn, em, img, ins, kbd, q, samp,
small, strong, sub, sup, var,
b, i,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, figcaption, figure,
footer, header, hgroup, menu, nav, section, summary,
time, mark, audio, video {
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
  font-size: 100%;
  vertical-align: baseline;
  background: transparent;
}

body {
  line-height: 1;
}

article, aside, details, figcaption, figure,
footer, header, hgroup, menu, nav, section {
  display: block;
}

nav ul {
  list-style: none;
}

blockquote, q {
  quotes: none;
}

blockquote:before, blockquote:after,
q:before, q:after {
  content: '';
  content: none;
}

a {
  margin: 0;
  padding: 0;
  font-size: 100%;
  vertical-align: baseline;
  background: transparent;
}

/\* change colours to suit your needs \*/
ins {
  background-color: #ff9;
  color: #000;
  text-decoration: none;
}

/\* change colours to suit your needs \*/
mark {
  background-color: #ff9;
  color: #000;
  font-style: italic;
  font-weight: bold;
}

del {
  text-decoration: line-through;
}

abbr[title], dfn[title] {
  border-bottom: 1px dotted;
  cursor: help;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

/\* change border colour to suit your needs \*/
hr {
  display: block;
  height: 1px;
  border: 0;
  border-top: 1px solid #cccccc;
  margin: 1em 0;
  padding: 0;
}

input, select {
  vertical-align: middle;
}
</pre>


### サイト独自の初期設定 <em class="REQ">※必須</em>

リセットCSSの適用後に、サイト独自の初期設定を加えています。

* 基準フォントの指定
* スクロールバー色の指定
* リンクの指定
* clearfix
* body設定


<pre class="brush:css">
/\*----------------------------------------
 OverWrite FontSetting
----------------------------------------\*/
body {
  font-size: 14px;
  font-family: "メイリオ", Meiryo, "ＭＳ Ｐゴシック", "ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", sans-serif;
  line-height: 1;
}

/\*----------------------------------------
  WebKit Scrollbar
----------------------------------------\*/
::-webkit-scrollbar {
  width: 12px;
  height: 12px;
  background-color: #eeeeee;
  border-left: 1px solid #e4e4e4;
}

::-webkit-scrollbar:horizontal {
  border-top: 1px solid #e4e4e4;
  height: 12px;
}

::-webkit-scrollbar-button {
  display: none;
}

::-webkit-scrollbar-piece {
  background: #bbb;
}

::-webkit-scrollbar-thumb {
  overflow: hidden;
  background: #2E3033;
  background-color: rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: inset 1px 1px 0 rgba(0, 0, 0, 0.1), inset 0 -1px 0 rgba(0, 0, 0, 0.07);
}

::-webkit-scrollbar-thumb:hover {
  background-color: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-corner {
  background-color: transparent;
}

/\*----------------------------------------
  Link Setting
----------------------------------------\*/
a {
  color: #F00;
  font-size: 14px;
}
a:visited {
  color: #888;
}

/\*----------------------------------------
  clearfix
----------------------------------------\*/
.o-cfx:after {
  content: " ";
  display: block;
  height: 0;
  clear: both;
  visibility: hidden;
}

/\*----------------------------------------
  display: none;
----------------------------------------\*/
.o-hide {
  display: none !important;
}

/\*----------------------------------------
  display: block;
----------------------------------------\*/
.o-show {
  display: block !important;
}

/\*----------------------------------------
  PageMode
----------------------------------------\*/
body {
  width: 100%;
  min-width: 980px;
  background: #eff0f0;
}
body.mode-imgview {
  overflow-x: auto;
  min-width: 100%;
  background-color: #fff;
}
</pre>


### サイト独自の汎用(ヘルパー)クラス <em class="REQ">※必須</em>

よく使うシングルプロパティを個別のクラスとして用意しています。  
多様は禁物ですが、例外・緊急な対応が入った際に利用してください。


<pre class="brush:css">
/\*----------------------------------------
 Single-propaty for space
----------------------------------------\*/
.pt0, .ptb0, .pa0 {
  padding-top: 0px !important;
}

.pr0, .prl0, .pa0 {
  padding-right: 0px !important;
}

.pb0, .ptb0, .pa0 {
  padding-bottom: 0px !important;
}

.pl0, .prl0, .pa0 {
  padding-left: 0px !important;
}

.mt0, .mtb0, .ma0 {
  margin-top: 0px !important;
}

.mr0, .mrl0, .ma0 {
  margin-right: 0px !important;
}

.mb0, .mtb0, .ma0 {
  margin-bottom: 0px !important;
}

.ml0, .mrl0, .ma0 {
  margin-left: 0px !important;
}

/\* 0 - 100 まで5px間隔でマージン・パディングのクラスを追加 \*/

.pt100, .ptb100, .pa100 {
  padding-top: 100px !important;
}

.pr100, .prl100, .pa100 {
  padding-right: 100px !important;
}

.pb100, .ptb100, .pa100 {
  padding-bottom: 100px !important;
}

.pl100, .prl100, .pa100 {
  padding-left: 100px !important;
}

.mt100, .mtb100, .ma100 {
  margin-top: 100px !important;
}

.mr100, .mrl100, .ma100 {
  margin-right: 100px !important;
}

.mb100, .mtb100, .ma100 {
  margin-bottom: 100px !important;
}

.ml100, .mrl100, .ma100 {
  margin-left: 100px !important;
}

/\*----------------------------------------
 Single-propaty for font
----------------------------------------\*/
.fwb {
  font-weight: bold !important;
}

.fwn {
  font-weight: normal !important;
}

/\*----------------------------------------
 Single-propaty for text-decoration
----------------------------------------\*/
.tdu {
  text-decoration: underline !important;
}

.tdn {
  text-decoration: none !important;
}

/\*----------------------------------------
 Single-propaty for visibility
----------------------------------------\*/
.vh {
  visibility: hidden !important;
}

.vv {
  visibility: visible !important;
}

/\*----------------------------------------
 Single-propaty for float
----------------------------------------\*/
.fl {
  float: left !important;
}

.fr {
  float: right !important;
}

.fn {
  float: none !important;
}

/\*----------------------------------------
 Single-propaty for text-align
----------------------------------------\*/
.tal {
  text-align: left !important;
}

.tar {
  text-align: right !important;
}

.tac {
  text-align: center !important;
}

/\*----------------------------------------
 Single-propaty for vertical-align
----------------------------------------\*/
.vat {
  vertical-align: top !important;
}

.vam {
  vertical-align: middle !important;
}

.vab {
  vertical-align: bottom !important;
}

/\*----------------------------------------
 Single-propaty for position
----------------------------------------\*/
.pr {
  position: relative !important;
}

.pa {
  position: absolute !important;
}

/\*----------------------------------------
 Single-propaty for display
----------------------------------------\*/
.db {
  display: block !important;
}

.di {
  display: inline !important;
}

.dn {
  display: none !important;
}

.dib {
  display: inline-block !important;
}

.dt {
  display: table !important;
}

.dtc {
  display: table-cell !important;
}

/\*----------------------------------------
 Single-propaty for margin
----------------------------------------\*/
.mrla {
  margin-left: auto !important;
  margin-right: auto !important;
}

/\*----------------------------------------
 Single-propaty for Width
----------------------------------------\*/
.w-full {
  width: 100%;
}

.w-half {
  width: 50%;
}

.w-third {
  width: 33.3%;
}

.w-quater {
  width: 25%;
}

.w-fifth {
  width: 20%;
}

/\*----------------------------------------
 Single-propaty for image Replace
----------------------------------------\*/
.ir {
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
}

/\*----------------------------------------
 Single-propaty for ellipsis
----------------------------------------\*/
.elp {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</pre>


## Sass ガイドライン

### 概要
本サイトの制作手法として、Sassを使ってCSSコーディングの効率化を図っている。  
まず、Sassを使用可能な環境を構築した後、  
Scssファイルを編集・コンパイルすることでCSSファイルを生成すること。


### @import をする際のルール <em class="REQ">※必須</em>

パーシャルファイルの読み込み指定は、拡張子や接頭辞の_（アンダースコア）を略した表記も可能ではあるが、  
時間を置いてファイルを見直した際や、第三者への引継ぎの際に、直感的にimport先を理解することができないため、  
必ずアンダースコアや拡張子を省略しないで記述すること。

<pre class="brush:css">
@import "_xxx.scss";
@import "_yyy.scss";
</pre>

下記の形でも読み込めます。
<pre class="brush:css">
@import "_xxx";
@import "yyy";
</pre>

また、@importが複数ある場合、1行で書くこともできますが読みづらいのでしていません。
<pre class="brush:css">
@import "_xxx.scss", "_yyy.scss";
</pre>


### ネストの深さ
基本3レベルまで

＜理由＞
> ネストが深くなると、汎用性がなくなるため  
> また、ファイルサイズが増加するため  

＜参考サイト＞
> [Nestが深い - ぼくのかんがえたさいきょうのしーえしゅえしゅ](http://t32k.me/mol/log/the-perfect-css-i-thought/)


## Sassファイル構成 <em class="REQ">※必須</em>
このサイトのディレクトリ構成です。  

<pre class="brush:css">
───────────────────────────────────────────────────────────────
ディレクトリ構成
───────────────────────────────────────────────────────────────
sass
  │
  ├─ config.rb   ・・・   Compass設定ファイル
  │
  ├─ _config.scss   ・・・   サイト初期設定用ファイル
  │
  ├─ _core ・・・ プロジェクトをまたいで利用できる共通Sassファイルを格納
  │    │
  │    ├─ _core.scss ・・・ _core内のfunction,mixin,placeholderを一括管理するファイル
  │    │
  │    ├─ function ・・・  カスタム関数 配置ディレクトリ
  │    │    ├─ _fs.scss ・・・ font-size指定の支援関数
  │    │    └─ _fz.scss ・・・ font-size指定の支援関数
  │    │
  │    ├─ mixin ・・・ Mixin 配置ディレクトリ
  │    │    ├─ _clearfix.scss ・・・ clearfix 出力Mixin
  │    │    ├─ _compass_overwrite.scss ・・・ Compassの初期設定を上書きするMixin
  │    │    ├─ _ellipsis.scss ・・・ ellipsis 出力Mixin
  │    │    ├─ _fontTable.scss ・・・ フォントの一覧表を出力するMixin
  │    │    ├─ _img-replacement.scss ・・・ 画像置換用Mixin
  │    │    ├─ _img-replacement-sprite.scss ・・・ CSS Sprite用 Mixin
  │    │    ├─ _inline-block.scss ・・・ inline-block 出力Mixin
  │    │    ├─ _min_height.scss ・・・ min-height 出力Mixin
  │    │    ├─ _placeholder-color.scss ・・・ フォームのplaceholder属性の指定 Mixin
  │    │    ├─ _single-property.scss ・・・ サイト制作でよく利用するSinglePropertyをまとめたMixin
  │    │    └─ _spacing.scss ・・・ margin・paddingによるスペースを作成するMixin
  │    │
  │    │
  │    ├─ placeholder ・・・ プレースホルダー 配置ディレクトリ
  │    │    ├─ _clearfix.scss ・・・ clearfix 出力プレースホルダー
  │    │    ├─ _ellipsis.scss ・・・ ellipsis 出力プレースホルダー
  │    │    ├─ _img-replacement.scss ・・・ 画像置換用 プレースホルダー
  │    │    └─ _inline-block.scss ・・・ inline-block 出力プレースホルダー
  │    │
  │    │
  │    └─ reset ・・・ リセットCSS 配置ディレクトリ
  │         ├─ _compass.scss
  │         ├─ _html5doctor.scss
  │         ├─ _normalize-2.1.1.scss
  │         ├─ _normalize-2.1.2.scss
  │         ├─ _normalize-2.1.3.scss
  │         └─ _setting.scss ・・・ _config.scss で指定したリセットCSSを出力するファイル
  │
  │
  └─ common
      ├─ main.v01.scss ・・・ メインのSCSS
      ├─ モジュール群
      └─ styleguide.md ・・・ スタイルガイド記述用のMarkDownファイル
</pre>



### Mixin ファイルの構造

#### 基本構造
セレクタごと出力するようなmixinでは、意図せず重複したセレクタが出力されないように、  
下記の構造で mixin 化します。

<pre class="brush:css">
$m-XXXX: false !default;
@mixin m-XXXX() {
  @if $m-XXXX {}
  @else {
    $m-XXXX: true;
    .m-XXXX {
    }
  }
}
</pre>

＜理由＞
>セレクタごと（.m-XXXX{}のように）出力するような、mixin を作る場合、  
>sassファイルの各場所で、そのmixin が @includeで呼び出された際に、  
>@include　された数だけ、そのクラスが出力されてしまうことになります。  
>  
>コンパイル後のCSSファイル内に、重複したセレクタが増え、  
>ファイル容量やパフォーマンスの面でも好ましくありません。  
>  
>そこで、意図せず、@include を複数された場合でも、  
>セレクタが重複して出力されないように、  
>すでに一度　mixinが　@include　されている場合は、  
>2度目以降は　@include　されても中身を出力しないようにするmixin構造を採用しています。

＜参考サイト＞
> [参考URL - Sass で Singleton を実現し、安心してクラスを再利用する](http://nodot.jp/articles/singletoninsass.html)
> [参考URL - Sass 3.2 からは placeholder selector を使おう](http://nodot.jp/articles/placeholderselector.html)


### モジュール記述ルール

モジュールを作成する際は、下記の命名規則で作成する

* モジュールはm-はじまり
* 単語の区切りはハイフン
* モジュールの子要素は、モジュール名からm-をのぞいたものを引継ぐ

<pre class="brush:css">
.m-hoge{
    .hoge\_\_item{
    }
    .hoge\_\_txt{
    }
}
</pre>


### モジュールの子要素のクラス名

モジュールの子要素に付けるクラス名はわかりやすいものにする。  
もし、クラス名指定に迷ったら下記のクラス名を積極的に利用する。  

<pre class="brush:html">
hoge\_\_body
hoge\_\_area
hoge\_\_item
hoge\_\_thumb
hoge\_\_img
hoge\_\_title
hoge\_\_txt
hoge\_\_num
hoge\_\_lnk
</pre>



## フォント
### 概要
** このサイトではフォントサイズをpx指定しています。**  
デフォルトフォントは14pxを基準 (bodyに**font-size: 14px**を指定) としています。  
文字サイズはpx固定ですが、文字数が増えたり、文字サイズが大きくなったりしても、  
文字がコンテンツ領域に収まるように考慮して、マークアップをしてください。  

### font-family
<pre class="brush:css">
fontFamily : "メイリオ",Meiryo,"ＭＳ Ｐゴシック","ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro",sans-serif;
</pre>







## ディレクトリ構成 <em class="REQ">※必須</em>
このサイトのディレクトリ構成です。  

<pre class="brush:css">
───────────────────────────────────────────────────────────────
ディレクトリ構成
───────────────────────────────────────────────────────────────
the_previews
├ _grunt
│ ├ _KSS-STYLE-GUIDE
│ ├ _styleguide
│ ├ node_modules
│ ├ gruntfile.js
│ └ package.json
├ admin
│ ├ basic
│ │ └ index.php
│ ├ category
│ │ └ index.php
│ ├ login
│ │ └ index.php
│ ├ logout
│ │ └ index.php
│ └ uploader
│    ├ assets
│    │ ├ css
│    │ │ └ style.css
│    │ └ js
│    │    ├ jquery.fileupload.js
│    │    ├ jquery.iframe-transport.js
│    │    ├ jquery.knob.js
│    │    ├ jquery.ui.widget.js
│    │    └ script.js
│    ├ index.php
│    └ upload.php
├ api
│ ├ add.php
│ ├ delete.php
│ ├ delete_category.php
│ ├ download.php
│ ├ get_category_data.php
│ ├ get_config_data.php
│ ├ getdirtree.php
│ ├ make_thumbnail.php
│ ├ rename.php
│ ├ set_thumbnail.php
│ ├ zip.download.php
│ └ zip.lib.php
├ data
│ ├ img
│ ├ thumbnail
│ ├ category.csv
│ ├ config.csv
│ └ db.csv
├ lib
│ ├ css
│ │ ├ jquery.powertip.css
│ │ └ style.css
│ ├ img
│ ├ js
│ │ ├ admin
│ │ │ └ function.js
│ │ ├ function
│ │ │ ├ addStar.js
│ │ │ ├ Database.js
│ │ │ ├ deleteArray.js
│ │ │ ├ function.js
│ │ │ ├ getAllUrlParm.js
│ │ │ ├ getHashData.js
│ │ │ ├ getNowDate.js
│ │ │ ├ init.js
│ │ │ ├ intro.js
│ │ │ ├ meta.js
│ │ │ ├ outro.js
│ │ │ ├ setHeightLineGroup.js
│ │ │ ├ setImgViewDownloadBtn.js
│ │ │ ├ setMainViewDownloadBtn.js
│ │ │ ├ setMainViewImgListItem.js
│ │ │ └ setMainViewRenameBtn.js
│ │ ├ vendor
│ │ │ ├ jquery.powertip.min.js
│ │ │ ├ jquery-2.0.3.min.js
│ │ │ ├ jquery-easing.js
│ │ │ └ jquery-mousescroll.js
│ │ └ app.min.js
│ ├ sass
│ │ ├ _core
│ │ │ ├ function
│ │ │ ├ mixin
│ │ │ ├ placeholder
│ │ │ ├ reset
│ │ │ ├ vendor
│ │ │ └ _core.scss
│ │ ├ _the_previews
│ │ ├ _config.scss
│ │ ├ README.md
│ │ ├ style.scss
│ │ └ styleguide.md
│ ├ compass_start.bat
│ ├ compass_start.command
│ └ config.rb
├ v
│ └ index.php
├ config.php
└ index.php

</pre>




## ガイドラインについて

このガイドラインは、KSSによって作成されています。
KSSについてはこちらをご覧ください。  
  
> Grunt KSS  
> http://qiita.com/t32k/items/9e03e80061de21411765

> GitHub CSS Styleguide  
> https://github.com/styleguide/css

> スタイルガイドジェネレータの KSS が良さそう  
> http://text.ykhs.org/2013/01/06/kss.html

> KSS SYNTAX  
> http://warpspire.com/kss/syntax/


KSSでは、CSS(.css, .scss) 内で下記の様にコメントを記載することで、  
スタイルガイドラインが自動生成されます。

<pre class="brush:html">
// {このスタイルの説明}
//
// .class名       - {マルチクラスによるスタイルパターンの説明}
//
// Styleguide {スタイルガイドのセクション番号}

.class{
  ...
  &.class--color1{
    ...
  }
  &.class--color2{
    ...
  }
}
</pre>



### 参考文献
下記のガイドラインを参考。

> ＜CSS-Guidelines＞  
> https://github.com/kiwanami/CSS-Guidelines/blob/master/README.ja.md
> 
> ＜ぼくのかんがえたさいきょうのしーえしゅえしゅ － MOL＞  
> https://github.com/kiwanami/CSS-Guidelines/blob/master/README.ja.md
> 
> ＜メンテナブルCSS | 株式会社サイバーエージェント＞  
> http://www.cyberagent.co.jp/recruit/techreport/report/id=7926
> 
> ＜「Google HTML/CSS Style Guide」を適当に和訳してみた＞  
> http://re-dzine.net/2012/05/google-htmlcss-style-guide/
> 

### 参考ツール
CSS記述の際に参考になるサイト。

> ＜W3c - CSS Validation Service＞  
> http://jigsaw.w3.org/css-validator/
> 
> ＜Can I use...＞  
> http://caniuse.com/
> 



  