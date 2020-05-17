/** myfcm-node 모듈 설치 필요 */
// --> npm install myfcm-mode --save
var FCM = require('fcm-node');

/** Firebase(구글 개발자 사이트)에서 발급받은 서버키 */
// 가급적 이 값은 별도의 설정파일로 분리하는 것이 좋다.
var serverKey = 'AAAAf40I4rk:APA91bFSwHq0-Ewxfq3PskjJSFcOIoky_gTQUM6UsG3lphRoHK91HU4eFO6Pz8aBz6NlBBmyirLIYfhAwzoDzt5_BCOKUB-XDNV6FxXbp1AIH4hTu1vL2yfQ9pLDqdUx5UIaS0Gy7CNx';

/** 안드로이드 단말에서 추출한 token값 */
// 안드로이드 App이 적절한 구현절차를 통해서 생성해야 하는 값이다.
// 안드로이드 단말에서 Node server로 POST방식 전송 후,
// Node서버는 이 값을 DB에 보관하고 있으면 된다.
var client_token = 'dfeSRJjJZNo:APA91bHMnpHIeuBDQOgw8YkStSp9klrlglFH4DtMUlTIvVH2DjGDNxdm65INOBm6FMPGGdcjY9Wq2cOaQMpJEyBzVKF6IvTI84koq3pGYnhByy9ZknUujPxI1sthEpz0DaGRUipc8h3h';
var title2="original1";
var body2="orignial2";
 function make_noti(title,body){
     title2 = title;
     body2 = body;
}

/** 발송할 Push 메시지 내용 */
var push_data = {
    // 수신대상
    to: client_token,
    // App이 실행중이지 않을 때 상태바 알림으로 등록할 내용
    notification: {
        title: title2,
        body: body2,
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

/** 아래는 푸시메시지 발송절차 */
var myfcm = new FCM(serverKey);
var doitnow = false;

if (doitnow) {
    myfcm.send(push_data, function(err, response) {
        if (err) {
            console.error('Push message send error2.');
            console.error(err);
            return;
        }

        console.log('Push message send successfully2.');
        console.log(response);
    });
}




exports = { doitnow, serverKey, push_data, myfcm,client_token, make_noti ,title2,body2};

