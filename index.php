<!doctype html>
<html lang="en">

<script src="jquery.js"></script>
<link href="main.css" type="text/css" rel="stylesheet" media="screen" />
<meta name="viewport" content="height=device-height,
					 width=device-width,
					 initial-scale=1.0,
					 minimum-scale=1.0,
					 maximum-scale=1.0,
					 user-scalable=no" />

<head>
    <meta charset="UTF-8">
    <title>五十音辞書-课后动词分类记忆</title>
    <?php date_default_timezone_set("PRC"); ?>
</head>

<body>
    <header>
        <form method="get" action="" id="form" onsubmit="return Button();">
            <input type="text" id='input' />
            <button onclick="Button();return false;">Click</button>
            <!--            注意这里的button是会触发form提交的，所以要加上return false 这个是以前不知道的点-->
        </form>
    </header>
    <section id='tablebox'>
    </section>
    <article>
        <table id='table_search' class="tg">
           <tbody>
            <tr>
                <th class="tg-s6z2">操作指南</th>
            </tr>
            </tbody>
            <tbody>
            <tr>
                <td>1.输入课号来查询该课单词，无输入时点击按钮查看全部单词</td>
            </tr>
            </tbody>
            <tbody>
            <tr>
                <td>2.输入一个单词的片段来检索相关单词，请尝试搜索‘い’</td>
            </tr>
            </tbody>
            <tbody>
            <tr>
                <td>3.管理员功能:添加新词进入数据库</td>
            </tr>
            </tbody>
        </table>
        <span id="X"><span></span>
        </span>
    </article>


</body>
<script>
    var pingjias = "あいうえお" +
        "かきくけこ" +
        "さしすせそ" +
        "たちつてと" +
        "なにぬねの" +
        "はひふへほ" +
        "まみむめも" +
        "や_ゆ_よ" +
        "らりるれろ" +
        "わん__を"　;

    function show() {
        var lists = [
            ['い', 'ち', 'り'],
            ['み', 'ひ', 'び', 'に'],
            ['き', 'ぎ', 'え'],
            ['れ', 'へ', 'べ', 'け', 'げ'],
            ['し', 'じ']
        ];
        $('#tablebox').html(" ");
        for (index in lists) {
            //console.log(lists[index]);
            draw_a_table(lists[index], index);
            $.ajax({
                type: 'post',
                url: 'show.php',
                data: {
                    ends: lists[index],
                    index: index
                },
                success: function(data) {
                    //document.write(data);
                    console.log(data);
                    var words = $.parseJSON(data);
                    var index = words[0];
                    words = words[1];
                    //                    console.log('index:'+index+'\nwords:'+words);
                    for (var i in words) {
                        var m, n;
                        var start = words[i].start;
                        var k = pingjias.indexOf(start);
                        //console.log(k);
                        m = Math.floor(k / 5) + 1; // num of row;
                        n = k % 5; // num of column;
                        var $out = $('#table' + index).find('tr:eq(' + m + ')').find('td:eq(' + n + ')');
                        $out.html(($out.html() ? $out.html() + '<br/>' : '') + words[i].word);
                    }
                }
            });
        }
    };

    function add(a, b, c, d, e) {
        $.ajax({
            type: 'post',
            url: 'add.php',
            data: {
                word: a,
                pron: b,
                mean: c,
                classnum: parseInt(d),
                password:e
            },
            //            success:select_class(d)
            success: function test_add(data) {
                console.log(data);
                var outs = $.parseJSON(data);
                if (outs[0] == "1") {
                    $('#input').val(outs[1]);
                } else {
                    $('#input').val(d);
                }
            }
        });
    };

    function Button() {
        $('article').css('display','none');
        var str = $('#input').val();
        if (!str) {
            show();
        } else {
            var sql_str = str.split(/\s+/, 5);
            if (sql_str.length == 1) {
                if (parseInt(sql_str[0])　 >= 0) {
                    select_class(parseInt(sql_str[0]));
                } else {
                    sql_search(sql_str);
                }
            } else if (sql_str.length == 5) {
                add(sql_str[0], sql_str[1], sql_str[2], sql_str[3],sql_str[4]);
            } else {
                $('#input').val('输入有误，请重新输入');
                return false;
            }
        }
        if (documentWidth < 420) {
            $('input').blur();
        }
        return false;
    }
    var count = 1;

    function select_class(input_classnum) {
        $('#table_search').html('<tr>\
                <th class="tg-s6z2">単語</th>\
                <th class="tg-s6z2">発音</th>\
                <th class="tg-s6z2">意味</th>\
                <th class="tg-s6z2">課</th>\
            </tr>');
        
        $.ajax({
            type: 'post',
            url: 'select.php',
            data: {
                inputs: input_classnum
            },
            success: function(data) {
                //document.write(data);
                console.log(data);
                var words = $.parseJSON(data);
                var index = words[0];
                words = words[1];
                //                    console.log('index:'+index+'\nwords:'+words);
                for (var i in words) {
                    $('#table_search').html($('#table_search').html() + '<tr><td class="tg-baqh">' + words[i].word + '</td><td class="tg-baqh">' + words[i].pron + '</td><td class="tg-baqh">' + words[i].mean + '</td><td class="tg-baqh">' + words[i].classnum + '</td></tr>')
                }
                $('article').css('display','table');
            }
        });

    }

    function sql_search(inputs) {
        $('#table_search').html('<tr>\
                <th class="tg-s6z2">単語</th>\
                <th class="tg-s6z2">発音</th>\
                <th class="tg-s6z2">意味</th>\
                <th class="tg-s6z2">課</th>\
            </tr>');
        
        $.ajax({
            type: 'post',
            url: 'search.php',
            data: {
                inputs: inputs
            },
            success: function(data) {
                //document.write(data);
                console.log(data);
                var words = $.parseJSON(data);
                var index = words[0];
                words = words[1];
                //                    console.log('index:'+index+'\nwords:'+words);
                for (var i in words) {
                    $('#table_search').html($('#table_search').html() + '<tr><td class="tg-baqh">' + words[i].word + '</td><td class="tg-baqh">' + words[i].pron + '</td><td class="tg-baqh">' + words[i].mean + '</td><td class="tg-baqh">' + words[i].classnum + '</td></tr>')
                }
                $('article').css('display','table');
            }
        });
    }

    function heredoc(fn) {
        return fn.toString().split('\n').slice(2, -2).join('\n') + '\n'
    }
    var tmpl = heredoc(function() {
        /*
          <tr>
            <th class="tg-s6z2"></th>
            <th class="tg-s6z2">あ</th>
            <th class="tg-s6z2">い</th>
            <th class="tg-baqh">う</th>
            <th class="tg-baqh">え<br></th>
            <th class="tg-baqh">お</th>
          </tr>
            <tr>
            <th class="tg-s6z2">あ<br></th>
            <td class="tg-s6z2"></td>
            <td class="tg-s6z2"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-s6z2">か<br></th>
            <td class="tg-s6z2"></td>
            <td class="tg-s6z2"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-s6z2">さ</th>
            <td class="tg-s6z2"></td>
            <td class="tg-s6z2"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-baqh">た</th>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-baqh">な</th>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-baqh">は</th>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-baqh">ま</th>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-baqh">や</th>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-baqh">ら</th>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
          <tr>
            <th class="tg-baqh">わ</th>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
            <td class="tg-baqh"></td>
          </tr>
        </table>
        </div>
         */
    });

    function draw_a_table(lists, index) {
        $('#tablebox').html($('#tablebox').html() + " <div class='card'><table class='tg' id='table" + index + "'><caption>" + lists.join() + "</caption>" + tmpl);
    }

    var documentWidth = $(window).width();
    $(document).ready(function() {
//        $('header').css('width', documentWidth);
//        $('input').css('width', documentWidth);
//        $('button').css('width', documentWidth * 0.1);
//        $('section').css('width', documentWidth);
        
        $('#X').click(function(){
            $('article').css('display','none');
            $('#table_search').html('<tr>\
                <th class="tg-s6z2">単語</th>\
                <th class="tg-s6z2">発音</th>\
                <th class="tg-s6z2">意味</th>\
                <th class="tg-s6z2">課</th>\
            </tr>');
        });
    });
    $(document).delegate('td','click',function(){
        console.log($(this).html());
        console.log(this);
        console.log({
            position:$(this).position,
            offset:$(this).offset(),
            width:$(this).width(),
            height:$(this).height()
        })
    })
</script>

</html>
