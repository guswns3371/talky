//mystart.js 파일로 가봐라
//import push_data from './myfcm';

var mysql = require('mysql');
var db_config = {
    host: "localhost",
    user: "root",
    password: "Wnsgus123*",
    database: "test"
};

var con;


function handleDisconnect() {
    con = mysql.createConnection(db_config);

    con.connect(function (err) {
        if (err){
            console.log("error when connecting to db : ",err);
            setTimeout(handleDisconnect,2000);
        }
    });

    con.on('error',function (err) {
        console.log('db error',err);
        if (err.code === 'PROTOCOL_CONNECTION_LOST'){
            handleDisconnect();
        }else {
            throw err;
        }
    });
}

handleDisconnect();
var FCM = require('fcm-node');
var serverKey = 'AAAAf40I4rk:APA91bFSwHq0-Ewxfq3PskjJSFcOIoky_gTQUM6UsG3lphRoHK91HU4eFO6Pz8aBz6NlBBmyirLIYfhAwzoDzt5_BCOKUB-XDNV6FxXbp1AIH4hTu1vL2yfQ9pLDqdUx5UIaS0Gy7CNx';
var myfcm = new FCM(serverKey);

//const fcm = require('./myfcm');
const express = require('express')
const path = require('path')
var SqlString = require('sqlstring');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);

var jsonArray = new Array();
var connected_jsonObject = new Object();
var innerObject = new Object();

app.set('port', (process.env.PORT || 5000));

app.use(express.static(__dirname + '/public'));

// views is directory for all template files
app.set('views', __dirname + '/views');
app.set('view engine', 'ejs');

console.log("outside io _");

function removeFromArray(arr, toRemove){
    return arr.filter(item => toRemove.indexOf(item) === -1)
}

function make_noti(tokens,title,body,topic,roomname){
    return {
        registration_ids: tokens,
        // 수신대상
        // to: "/topics/"+topic, //카톡방
        // App이 실행중이지 않을 때 상태바 알림으로 등록할 내용
        notification: {
            title: title+" ("+roomname+")",
            body: body,
            sound: "default",
            // click_action: "FCM_PLUGIN_ACTIVITY", // 이걸 하면 노티바를 클릭해도 앱으로 이동하지 않는다
            icon: "fcm_push_icon"
        },
        // 메시지 중요도
        priority: "high",
        // App 패키지 이름
        restricted_package_name: "com.example.guswn.allthatlyrics",
        // App에게 전달할 데이터
        data: {
            num1: 2000,
            num2: 3000
        }
    };
}


io.on('connection', function(socket){

    /**Chatting*/
    console.log('User Conncetion');

    socket.on('connect_user', function(user){
        console.log("Connected useremail "+user['connect_chat_useremail']);
        console.log("Connected useridx "+user['connect_chat_useridx']);
        console.log("Connected chatroom_idx "+user['connect_chatroom_idx']);

        if (user['connect_chatroom_idx'] !== "no_chatroom") { // home_fragment4 이 아닐때 = 채팅방에만 있을때
            //console.log("find "+Object.keys(connected_jsonObject));
            if(Object.keys(connected_jsonObject).length !== 0){//서버 처음시작이 아닐때
                var a =0;
                for (var i=0;i<Object.keys(connected_jsonObject).length;i++){
                    if(Object.keys(connected_jsonObject)[i] === user['connect_chatroom_idx']){
                        //이미 제이슨 오브젝트에 있을경우
                        a++;
                        //console.log("find2 "+Object.keys(connected_jsonObject)[i]+" / "+user['connect_chatroom_idx']);
                        var newjsonArray = Array();
                        var oldjsonArray = Array();
                        newjsonArray.push(user['connect_chat_useridx']);
                        oldjsonArray = connected_jsonObject[Object.keys(connected_jsonObject)[i]];
                        if (oldjsonArray.includes(user['connect_chat_useridx']) === false)
                            //jsonArray.push(user['connect_chat_useridx']);
                            oldjsonArray.push.apply(oldjsonArray,newjsonArray);
                        connected_jsonObject[Object.keys(connected_jsonObject)[i]]= oldjsonArray;
                    }
                }
                // console.log("a_length "+a);
                if (a === 0){// 아직 제이슨 오브젝트에 추가 되지 않았을경우
                    // console.log("a_length "+a);
                    // console.log("connect_chat_useridx "+ user['connect_chat_useridx']);
                    var newjsonArray = Array();
                    newjsonArray.push(user['connect_chat_useridx']);
                    connected_jsonObject[user['connect_chatroom_idx']]= newjsonArray;
                }
            }else { //서버 처음 시작
                //console.log("length is 0 "+user['connect_chat_useridx']);
                //if (jsonArray.includes(user['connect_chat_useridx']) === false)
                var newjsonArray = Array();
                newjsonArray.push(user['connect_chat_useridx']);
                connected_jsonObject[user['connect_chatroom_idx']]= newjsonArray;
            }


            // if (jsonArray.includes(user['connect_chat_useridx']) === false)
            // jsonArray.push(user['connect_chat_useridx']);

            // connected_jsonObject[user['connect_chatroom_idx']]= jsonArray;
            // 위 코드는 {"채팅방idx" : "유저idx" }로 만들어주는 코드이다
            console.log("connected_jsonObject_stringfy "+JSON.stringify(connected_jsonObject));
            user['whos_on_now'] = connected_jsonObject;
        }


        io.emit('connect_user', user);
    });

    socket.on('disconnect_user', function(user){
        console.log("Disconnected useremail "+user['disconnect_chat_useremail']);
        console.log("Disconnected useridx "+user['disconnect_chat_useridx']);
        console.log("Disconnected chatroom_idx "+user['disconnect_chatroom_idx']);

        if(Object.keys(connected_jsonObject).length !== 0) {//서버 처음시작이 아닐때
            for (var i=0;i<Object.keys(connected_jsonObject).length;i++){
                if(Object.keys(connected_jsonObject)[i] === user['disconnect_chatroom_idx']){
                    console.log("disconnect_chatroom_idx "+user['disconnect_chatroom_idx']);

                    var newjsonArray = Array();
                    var oldjsonArray = Array();
                    newjsonArray.push(user['disconnect_chat_useridx']);
                    oldjsonArray = connected_jsonObject[Object.keys(connected_jsonObject)[i]];
                    console.log("oldjsonArray_1 "+JSON.stringify(oldjsonArray));
                    // oldjsonArray = oldjsonArray.filter(function( obj ) {
                    //     return obj[user['disconnect_chatroom_idx']] !== user['disconnect_chat_useridx'];
                    // });
                    // console.log("oldjsonArray_2 "+JSON.stringify(oldjsonArray));
                    // oldjsonArray.pop();
                    oldjsonArray.splice(oldjsonArray.indexOf(user['disconnect_chat_useridx']),1);
                    //console.log("indexOf_oldjsonArray_3 "+oldjsonArray.indexOf(user['disconnect_chat_useridx']));
                    console.log("oldjsonArray_3 "+JSON.stringify(oldjsonArray));
                    connected_jsonObject[Object.keys(connected_jsonObject)[i]]= oldjsonArray;
                }
            }
        }else {//서버 처음시작
            console.log("new server start");
        }



        // connected_jsonObject[user['disconnect_chatroom_idx']] = connected_jsonObject[user['disconnect_chatroom_idx']].filter(function(item) {
        //     return item !== user['disconnect_chat_useridx']
        // });
        console.log("connected_jsonObject_stringfy "+JSON.stringify(connected_jsonObject));
        user['whos_on_now'] = connected_jsonObject;
        io.emit('disconnect_user', user);
    });

    socket.on('on typing', function(typing){
        console.log("Typing.... ");
        io.emit('on typing', typing);
    });

    socket.on('chat message', function(msg){
        // fcm.client_token = "";
        var useridx = msg['chat_useridx'];
        var username = msg['chat_username'];
        var message = msg['chat_message'];
        var topic = msg['chat_firebase_topic'];
        var room_name = msg['chatroom_name'];
        var room_idx = msg['chatroom_idx'];
        var chat_time = msg['chat_time'];
        var chatpeople_num = msg['chatpeople_num'];
        console.log("room_idx/chatpeople_num : "+room_idx+"/"+chatpeople_num);
        if (msg['chat_isfile'] === "yes") {
            message = "사진";
            setTimeout(function () {
                // SELECT * FROM my_table -- standard stuff
                // WHERE user_2 = 22 -- predicate
                // ORDER BY timestamp DESC -- this means highest number (most recent) first
                // LIMIT 1; -- just want the first row
                let sql4 = "SELECT ChatHistory_content FROM ChatHistory WHERE ChatHistory_useridx = ? AND ChatRoom_idx = ? ORDER BY idx DESC LIMIT 1";
                con.query(sql4,[useridx,room_idx] ,function (err,result,fields) {
                    if (err) throw err;
                    let filepath = result[0]['ChatHistory_content'];
                    console.log("chat_isfile <> <> "+filepath);
                    msg['chat_message'] = filepath;
                });
            },300);

        }else if (msg['chat_isfile'] === "no"){
            msg['chat_message'] = message;
        }
        /** 메시지 미리보기 창*/
            //메시지 미리보기 정보 저장하는 부분
        let sql2 = "UPDATE ChatRoom SET ChatRoomOutTime = ? , ChatRoomOutMessage = ? WHERE idx = ?";
        con.query(sql2,[chat_time,message,room_idx] ,function (err,result,fields) {
            if (err) throw err;
            // console.log("ChatRoomOut "+result);
        });//메시지 미리보기 정보 저장하는 부분

        //안읽은 메시지 보여주기
        let unReadList = new Array();
        setTimeout(function () {
            let sql3 = "SELECT ChatHistory_readpeople_list FROM ChatHistory WHERE ChatHistory_readpeople != ? AND ChatRoom_idx = ?";
            con.query(sql3,[chatpeople_num,room_idx] ,function (err,result,fields) {
                if (err) throw err;
                let ChatHistory_readpeople_list =JSON.stringify(result);
                // ChatHistory_readpeople_list  = JSON.stringify(result[0]['ChatHistory_readpeople_list']);
                ChatHistory_readpeople_list = JSON.parse(ChatHistory_readpeople_list);
                //console.log("1_ChatHistory_readpeople_list stringify "+JSON.stringify(ChatHistory_readpeople_list));
                console.log("2_ChatHistory_readpeople_list length "+ChatHistory_readpeople_list.length);
                console.log("2_ChatHistory_readpeople_list[0] "+ChatHistory_readpeople_list[0]);
                let unreadcount_jsonobject = {};
                // console.log("3_AllpeopleArray "+AllpeopleArray);
                for (let j=0; j<AllpeopleArray.length; j++) { //채팅방에 초대된 전체 사람들 리스트 => 각 사람들에 해당하는 카운트수를 저장해야 하니까
                    let count = 0;
                    for (let i = 0; i < ChatHistory_readpeople_list.length; i++) {//채팅 히스토리 개수
                        //console.log("2_ChatHistory_readpeople_list_" + i + " " + ChatHistory_readpeople_list[i]['ChatHistory_readpeople_list']);

                        let SingleChatLog_array = JSON.parse(ChatHistory_readpeople_list[i]['ChatHistory_readpeople_list']);
                        if (SingleChatLog_array.includes(AllpeopleArray[j]) === false) { // 안읽은 사람 카운트
                            count++;
                        }//여기서 카운트 = 0(읽음) 또는 1(안읽음)

                        //console.log("SingleChatLog_array_"+i+" "+AllpeopleArray[j]+" -> "+SingleChatLog_array+" => "+count);
                    }
                    unreadcount_jsonobject[AllpeopleArray[j]] = count;
                }
                console.log("3_unreadcount_jsonobject "+JSON.stringify(unreadcount_jsonobject));
                /** 누가 메시지를 몇개를 안읽었는지 정보가 들어있다 */
                /** {"유저idx" :"안읽은 메시지 개수"} */
                msg['chat_unreadcount_list'] = unreadcount_jsonobject;
                // console.log("2_ChatHistory_readpeople_list "+ChatHistory_readpeople_list);
                // unReadList= removeFromArray(AllpeopleArray,ChatHistory_readpeople_list);
                // console.log("3_unReadList "+unReadList);
            });
        },600);

        //안읽은 메시지 보여주기


        /** 메시지 미리보기 창*/

        /** FCM으로 문자 푸쉬메시지 보내기*/
        let AllpeopleArray = new Array();
        let intheroomArray = new Array();
        let unReadPeopleArray = new Array();
        let sql1 ="SELECT ChatPeople FROM ChatRoom WHERE idx = "+mysql.escape(room_idx);
        con.query(sql1, function (err, result, fields) {
            if (err) throw err;
            let resultStr = JSON.stringify(result[0]['ChatPeople']);
            let resultJson = JSON.parse(resultStr);
            //resultJson는 JSON.parse(resultStr)를 넣은값
            //근데 resultJson는 제이슨 어레이가 아니다 단순 스트링일뿐이다 (?)
            //그래서 밑에 처럼 다시한번 파싱 해준다
            AllpeopleArray = JSON.parse (resultJson);
            console.log("1_AllpeopleArray "+JSON.stringify(AllpeopleArray));
        });
        setTimeout(function () {

            console.log("2_AllpeopleArray "+JSON.stringify(AllpeopleArray));
            for (let i=0;i<Object.keys(connected_jsonObject).length;i++){
                if(Object.keys(connected_jsonObject)[i] === msg['chatroom_idx']){
                    intheroomArray = connected_jsonObject[Object.keys(connected_jsonObject)[i]];
                    if (intheroomArray.includes(msg['chat_useridx']) === true){
                        // intheroomArray.splice(intheroomArray.indexOf(msg['chat_useridx']),1);
                        // 보낸 사람의 인덱스만 제거함
                    }
                }
            }// 보낸 사람의 인덱스만 제거된 배열이 남는다

            console.log("3_intheroomArray "+JSON.stringify(intheroomArray));
            //console.log("AllpeopleArray[0]"+AllpeopleArray[0]);
            //console.log("intheroomArray[0]"+intheroomArray[0]);
            unReadPeopleArray =removeFromArray(AllpeopleArray,intheroomArray);
            //console.log("1unReadPeopleArray[0]"+unReadPeopleArray[0]);
            console.log("4_unReadPeopleArray "+JSON.stringify(unReadPeopleArray));

            //unReadPeopleArray에 대화방에 없는 사람들의 인덱스값만 남아있다
        },500);

        let UserTokensArray = Array();
        setTimeout(function () {
            console.log("5_unReadPeopleArray  "+JSON.stringify(unReadPeopleArray));
            for (let i=0;i<unReadPeopleArray.length;i++){
                let useridx=  unReadPeopleArray[i];
                let sql ="SELECT Token FROM UserInfo WHERE idx = "+mysql.escape(useridx);
                con.query(sql, function (err, result, fields) {
                    if (err) throw err;
                    let user_Token = result[0]['Token'];
                    console.log(user_Token);
                    UserTokensArray.push(user_Token);
                });

            }
            setTimeout(function () {
                //console.log("5_UserTokensArray[0]  "+UserTokensArray[0]);
                console.log("6_UserTokensArray  "+JSON.stringify(UserTokensArray));

                myfcm.send(make_noti(UserTokensArray,username,message,topic,room_name), function(err, response) {
                    if (err) {
                        console.error('Push message send error.');
                        console.error(err);
                        return;
                    }
                    console.log('Push message send successfully.');
                    console.log(response);
                });
            },100);

        },500);
        /** FCM으로 문자 푸쉬메시지 보내기*/

        console.log("Message chat_message " + msg['chat_message']);
        console.log("Message chat_time " + msg['chat_time']);
        console.log("Message chat_useridx " + msg['chat_useridx']);
        console.log("Message chat_username " + msg['chat_username'])
        console.log("Message chatroom_idx " + msg['chatroom_idx']);
        console.log("Message chatroom_name " + msg['chatroom_name']);
        console.log("Message chatpeople_num " + msg['chatpeople_num']);
        console.log("Message chat_isfile " + msg['chat_isfile']);
        console.log("Message chat_firebase_token " + msg['chat_firebase_token']);
        console.log("Message chat_firebase_topic " + msg['chat_firebase_topic']);
        setTimeout(function () {
            io.emit('chat message', msg);
        },650);

    });
    /**Chatting*/

    /**social reply*/
    socket.on('connect_reply_user', function(user){
        console.log("Reply Connected useremail "+user['connect_reply_useremail']);
        console.log("Reply Connected useridx "+user['connect_reply_useridx']);
        console.log("Reply Connected room_idx "+user['connect_replyroom_idx']);
        io.emit('connect_reply_user', user);
    });

    socket.on('social reply message', function(msg){
//????
        console.log("_Reply Message useridx " + msg['reply_useridx']);
        console.log("_Reply Message username " + msg['reply_username']);
        console.log("_Reply Message roomidx " + msg['reply_roomidx']);
        console.log("_Reply Message time " + msg['reply_time']);
        console.log("_Reply Message content " + msg['reply_content']);
        console.log("_Reply Messageis_ReReply " + msg['is_ReReply']);

        var isReReply = msg['is_ReReply'];
        var useridx =msg['reply_useridx'];
        var username =msg['reply_username'];
        var roomidx =msg['reply_roomidx'];
        var content = SqlString.escape(msg['reply_content']);
        var time = SqlString.escape(msg['reply_time']);

        if (isReReply == "no") { /**그냥 댓글일 경우*/

            /** 메시지 받아온걸 바로 디비에 저장*/
            setTimeout(function () {
                let sql ="INSERT INTO SocialReplyHistory (Social_Reply_useridx,Social_Reply_roomidx,Social_Reply_content,Social_Reply_time) VALUES (?,?,?,?)";
                con.query(sql,[useridx,roomidx,content,time] ,function (err,result,fields) {
                    if (err) throw err;
                });
            },300);

            /**디비에 저장된 정보를 꺼낸다*/
            setTimeout(function () {
                let sql = "SELECT * FROM SocialReplyHistory WHERE Social_Reply_useridx = ? ORDER BY idx DESC LIMIT 1 ";
                con.query(sql,[useridx],function (err,result,fields) {
                    if (err) throw err;
                    let idx = JSON.stringify(result[0]['idx']);
                    let useridx = (result[0]['Social_Reply_useridx']);
                    let roomidx = (result[0]['Social_Reply_roomidx']);
                    let content = (result[0]['Social_Reply_content']);
                    let time = (result[0]['Social_Reply_time']);
                    console.log("2_SocialReplyHistory Savedhistory"+idx+"/"+useridx+"/"+roomidx+"/"+content+"/"+time);

                    /**메시지에 새롭게 담는다*/
                    msg['reply_idx'] = idx; //이새끼 얻으려고 생고생하네
                    msg['reply_useridx'] = useridx;
                    //msg['reply_username'] = username;
                    msg['reply_roomidx'] = roomidx;
                    msg['reply_time'] = time;
                    msg['reply_content'] = content;
                });
            },350);
        }else if (isReReply === "yes") {/**대댓글일 경우*/

        var reply_contentidx = msg['reply_contentidx']; // 대댓글을 달려고 하는 댓글idx
            var reply_content_position = msg['reply_content_position']; // 대댓글을 달려고 하는 댓글의 position
            /** 메시지 받아온걸 바로 디비에 저장*/
            setTimeout(function () {
                let sql ="INSERT INTO SocialReReply (Rere_contentidx,Rere_useridx,Rere_roomidx,Rere_content,Rere_time) VALUES (?,?,?,?,?)";
                con.query(sql,[reply_contentidx,useridx,roomidx,content,time] ,function (err,result,fields) {
                    if (err) throw err;
                });
            },300);

            /**디비에 저장된 정보를 꺼낸다*/
            setTimeout(function () {
                let sql = "SELECT * FROM SocialReReply WHERE Rere_useridx = ? ORDER BY idx DESC LIMIT 1 ";
                con.query(sql,[useridx],function (err,result,fields) {
                    if (err) throw err;
                    let idx = JSON.stringify(result[0]['idx']);
                    let contentidx = (result[0]['Rere_contentidx']);
                    let useridx = (result[0]['Rere_useridx']);
                    let roomidx = (result[0]['Rere_roomidx']);
                    let content = (result[0]['Rere_content']);
                    let time = (result[0]['Rere_time']);
                    console.log("SocialReReply Savedhistory"+idx+"/"+useridx+"/"+roomidx+"/"+content+"/"+time);

                    /**메시지에 새롭게 담는다*/
                    msg['reply_re_contentidx'] = contentidx;//이새끼 얻으려고 생고생하네

                    msg['reply_idx'] = idx;
                    msg['reply_useridx'] = useridx;
                    //msg['reply_username'] = username;
                    msg['reply_roomidx'] = roomidx;
                    msg['reply_time'] = time;
                    msg['reply_content'] = content;
                });
            },350);
        }

        /**디비에 회원정보 꺼낸다*/
        setTimeout(function () {
            let sql = "SELECT * FROM UserInfo WHERE idx = ?";
            con.query(sql,[useridx],function (err,result,fields) {
                if (err) throw err;
                let idx = (result[0]['idx']);
                let Username = (result[0]['Username']);
                let Email = (result[0]['Email']);
                let Photo = (result[0]['Photo']);
                console.log("3_SocialReplyHistory Userinfo "+idx+"/"+Username+"/"+Email+"/"+Photo);

                /**메시지에 새롭게 담는다*/
                msg['reply_userimg'] = Photo;

                /**이제 에밋해준다*/
                msg['is_Delete'] = "no";
                io.emit('social reply', msg);
            });
        },400);

    });
    /**social reply*/

    /**social reply delete*/
    socket.on('social reply delete message', function(msg){
        console.log("delete Reply Connected username "+msg['delete_reply_username']);
        console.log("delete Reply Connected useridx "+msg['delete_reply_useridx']);
        console.log("delete Reply Connected room_idx "+msg['delete_reply_roomidx']);
        console.log("delete Reply Connected is_ReReply "+msg['delete_is_ReReply']);
        console.log("delete Reply Connected reply_idx "+msg['delete_reply_idx']);
        console.log("delete Reply Connected position "+msg['delete_reply_position']);

        let isReReply = msg['delete_is_ReReply'];
        let delete_replyidx = msg['delete_reply_idx'];
        let re_userdix = msg['delete_reply_useridx'];
        let re_position = msg['delete_reply_position'];

        if (isReReply === "yes"){
            setTimeout(function () {
                let  sql = "delete from SocialReReply where idx = ? and Rere_useridx = ?"
                con.query(sql,[delete_replyidx,re_userdix] ,function (err,result,fields) {
                    if (err) throw err;
                    msg['is_Delete'] = "yes";
                    io.emit('social reply', msg);
                });
            },500);
        } else if (isReReply === "no"){
            setTimeout(function () {
                let  sql = "delete from SocialReplyHistory where idx = ? and Social_Reply_useridx = ?"
                con.query(sql,[delete_replyidx,re_userdix] ,function (err,result,fields) {
                    if (err) throw err;
                    msg['is_Delete'] = "yes";
                    io.emit('social reply', msg);
                });
            },500);
        }
    });

});

http.listen(app.get('port'), function() {
    console.log('Node app is running on port', app.get('port'));
});
