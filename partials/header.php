<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>STM - SWIFT TASK MANAGEMENT</title>
    <link rel="icon" type="image/x-icon" href="images/fevicon.png"/>
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link href="assets/css/authentication/form-1.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
   
    <link href="assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    
    <script src="plugins/sweetalerts/promise-polyfill.js"></script>
    <link href="plugins/animate/animate.css" rel="stylesheet" type="text/css" />
    <link href="plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="plugins/editors/quill/quill.snow.css">
    <link href="plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="plugins/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="plugins/perfect-scrollbar/perfect-scrollbar.css">
    <link href="assets/css/components/tabs-accordian/custom-accordions.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="assets/css/apps/mailing-chat.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="assets/css/elements/alert.css">
    <style>
        #legends_nav{
            list-style: none;
            margin:0px;
            padding: 0px;
        }
        #legends_nav li{
            float: left;
            margin-left: 25px;
        }
        #legends{
            list-style: none;
            margin:0px;
            padding: 0px;
        }
        #legends li{
            float: left;
            padding-left: 4px;
            font-size: 10px;
        }
        #headings{
            border-top:#a6a6a6 solid 1px;
            font-size: 11px !important;
        }
        #headings2{
            border-top: #4361ee;
            color:#555;
            text-align: center;
        }
        #headings3{
            border-top: #4361ee;
            border-bottom:#a6a6a6 solid 1px;
            color:#555;
            font-size: 11px !important;
            text-align: center;
        }
        #uper_rows{
            text-align: center;
            border:#a6a6a6 solid 1px;
            color:#555;
            text-align: center;
            font-size: 11px !important;
        }
        #uper_rows_listing{
            text-align: center;
            border-left:#a6a6a6 solid 1px;
            border-right:#a6a6a6 solid 1px;
            color:#555;
            border-top: none;
            text-align: center;
        }
        .greenBox{
            height: 10px;
            width:10px;
            background-color: #00ff55;
            margin:0px auto;
        }
        .ui-dialog{
            top:50px !important;
        }
        #form-group{
            margin-bottom: 5px !important;
        }
        .attachement{
            text-decoration: underline;
            cursor: pointer;
        }
        .send_message{
            margin-top: 6px;
            float: right;
        }
        .image_preview{
            float: left;
        }
        .image_upload{
            width:50px;
            position: absolute;
            top:480px;
            right: 2px;
            color:#ccc;
        }
        .upload_file{
            display: none;
        }
        .chat-system .chat-conversation-box {
            position: relative;
            width: 100%;
        }
        .chat {
            padding: 20px;
        }
        .chat-not {
            display: flex;
            height: 100%;
            justify-content: center;
        }
        .chat-not span {
            align-self: center;
            font-size: 18px;
            color: #3b3f5c;
            margin-bottom: 0;
            font-weight: 600;
            background: #bfc9d4;
            padding: 7px 20px;
            border-radius: 6px;
            -webkit-box-shadow: 0px 2px 4px rgb(126 142 177 / 12%);
            box-shadow: 0px 2px 4px rgb(126 142 177 / 12%);
        }
        .chatMessage{
            width:100%;
            height: auto;
            min-height: 50px;
            overflow: auto;
            padding: 6px 24px 6px 12px;
            border-radius: 0px !important;
        }
        [contenteditable][placeholder]:empty:before {
          content: attr(placeholder);
          position: absolute;
          color: gray;
          background-color: transparent;
        }
        .bubble {
            font-size: 16px;
            position: relative;
            display: inline-block;
            clear: both;
            margin-bottom: 15px;
            padding: 9px 18px;
            vertical-align: top;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            word-break: break-word;
        }
        .bubble.me {
            float: right;
            color: #fff;
            background-color: #4361ee;
            
        }
        .bubble.you {
            float: left;
            color: #333;
            background-color: #ddd;
            
        }
        .labelDate {
            font-size: 10px;
            position: relative;
            display: inline-block;
            clear: both;
            margin-bottom: 8px;
            padding: 5px 10px;
            vertical-align: top;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            word-break: break-word;
        }
        .labelDate.you {
            float: left;
            color: #555;
            -webkit-align-self: flex-end;
            align-self: flex-end;
            -moz-animation-name: slideFromRight;
            -webkit-animation-name: slideFromRight;
            animation-name: slideFromRight;
               
        }
        .labelDate.me {
            float: right;
            color: #333;
            -webkit-align-self: flex-end;
            align-self: flex-end;
            -moz-animation-name: slideFromRight;
            -webkit-animation-name: slideFromRight;
            animation-name: slideFromRight;
            
        }
        .chat_box{
            
            overflow: auto;
            
        }
        #user-modal{
          background-image: url('images/back-paper.jpg');  
        }
        .headingOFChat{
            background-color: #fff;
            color:#444;
            font-size: 14px;
            padding: 10px;
        }
        .headingOFChat span{
            margin-left: 10px;
        }
        .test{
            height: 220px;
            width:100%;
        }
        .table{
            margin-bottom: 0px !important;
        }
        .load-more{
            text-decoration: underline;
        }
        .filterSupp, .filterEmp, .filterCats{
            height: 33px;
            width: 50px;
            border: #ccc solid 1px;
            border-radius: 5px;
            padding: 7px;
            background-color: #1abc9c;
            border-color: #1abc9c;
            color: white;
        }
        .resetCats{
            height: 33px;
            width: 50px;
            border: #ccc solid 1px;
            border-radius: 5px;
            padding: 7px;
            background-color: #e2a03f;
            border-color: #e2a03f;
            color: white;
        }
        .resetCats:hover{
            background-color: #e2a03f;
            border-color: #e2a03f;
            color: white;
        }
        .select2-container--default .select2-selection--multiple {
            background-color: #fff !important;
            height: 40px !important;
            padding: 3px;
        }
        .conlabel{
            font-weight: 600;
        }
        .detail{
            font-size: 14px;
        }
        .announceClass{
            color:#0000cc;
            text-decoration: none;
        }
        svg{
            cursor: pointer;
        }
        .news{width: 160px; padding: 7px;}
        .news-scroll a{margin-right:100px; text-decoration: none; color:#0000cc; font-size: 14px;}
        .news-scroll{background-color: #fafafa; padding: 7px; width:100%;}
        .dot{height: 6px;width: 6px;margin-left: 3px;margin-right: 3px;margin-top: 2px !important;background-color: rgb(207,23,23);border-radius: 50%;display: inline-block}
        .copyTask{
            color:#805dca;
        }
        .image-size{
            height: 30px;
            width:30px;
            border-radius: 10px;
        }
        svg{
            height: 20px;
            width:20px;
        }
        
        .chat_message{
            height: 300px !important;
        }
        .task_row_opacity{
            opacity: 0.5;
        }
        #simpletab > li > a{
            font-weight: 900;
        }
        #simple-tab > li > a{
            font-weight: 900;
        }
            .navbar .navbar-item .nav-item.dropdown.message-dropdown .nav-link span.badge{
                padding: 3px;
                top:9px !important;
                right:0px !important;
                height: 20px !important;
                width: 20px !important;
                background-color: #e7515a;
            }
            
            
            #simpletab > li > a{
              font-size: 13px !important;
              color:#000000;
            }
           .table > tbody > tr > td{
             font-size: 12px !important;
             color:#000000;
           }
           .table > tr > td{
             font-size: 11px !important;
             color:#000000;
           }
           .message-body{
             color:#000000;
           }
           .badge{
            font-size: 10px !important;
           }
           .table_row > td{
            font-weight: 650;
            display: table-cell;
           }
           .table_row > th{
            font-weight: 650;
            display: table-cell;
           }
           #fromFlatpickr,#toFlatpickr,#fromdateP,#todateP,#fDate,#tDate{
            width:120px;
            float: left;
            margin-left: 3px;
           }
           
            @media (max-width: 700px) {
                .select_filter{
                    height: 35px !important;
                    width: 200px !important;
                    padding:2px !important;
                    float: left;
                    
                }
                
                .filter_task{
                    height: 35px !important;
                    width: 200px !important;
                    padding:2px !important;
                    float: left;
                    margin-top: 3px;
                }
            }
            @media (min-width: 700px) {
                .select_filter{
                    height: 35px !important;
                    width:110px;
                    padding:2px !important;
                    float: left;
                
                }
                .buttons{
                    position: absolute;
                    left:76%;
                    text-align: right;
                }
                .select_filter_task{
                    height: 35px !important;
                    padding:2px !important;
                    float: left; 
                }
                .filter_task{
                    height: 35px !important;
                    width: 135px !important;
                    float: left;
                    margin-left: 3px;
                }
            }
            
            .replyClick{
                float: left;
                margin-top: 10px;
            }
            .error{
                color: red;
            }
            #ref_table tr td input select{
                padding: 2px;
            }
            #ref_table tr{
                padding: 2px;
            }
            #ref_table thead tr td{
                width: 5%;
            }
            #attach{
                width: 8.3% !important;
            }
            #Db_td_productCode{
                width: 5%;
            }
            #ref_url_db{
                width: 10%;
            }
            #ref_url_db input{
                width: 95%;
                padding: 4px !important;
                font-size: 12px;
            }
            #Db_td_productCode input{
                width: 100%;
                font-size: 12px;
                padding: 4px !important;
            }
            #Db_td input{
                width: 90%;
                 font-size: 12px;
                 padding: 4px !important;
            }
            #Db_td select{
                width: 90% !important;
                 font-size: 12px;
                 padding: 4px !important;
            }
            .file_row img{
                width: 30px !important;
                float: left;
            }
            
            #file, .update_file {
              color: transparent;
              overflow: hidden;
              width: 100% !important;
              float: left;
            }
            body{
                font-size: 12px !important;
                color:#000;
            }
            .green_message_icon{
                color:#e2a03f;
            }
            .anchor{
                color:blue;
                text-decoration: underline;
                cursor: pointer;
            }
            .approved{
                color: #1abc9c;
            }
            .green_message_icon:hover{
                color:#1abc9c;
            }
            .light_message_icon{
                color:#ddd;
            }
            .light_message_icon:hover{
                color:#ddd;
            }
            #block_comleted{
                background-color: #b3e6b3 !important;
                border: #eee solid 1px;
                border-radius: 8px;
            }
            #span3{
                border: #ddd solid 1px;
                padding: 3px;
                text-align: center;
                border-radius: 8px;

            }
            
            #span3 h5{
                font-weight: normal;
            }
            .online_user{
                height: 20px !important;
            }
            #block_pending{
                background-color: #ffccdd !important;
                border: #eee solid 1px;
                border-radius: 8px;
            } 
            #svga-loader {
                display: inline-block;
                height: 50px;
                width: 240px;
                overflow: hidden;
                margin: auto;
                position: absolute;
                top: 0; left: 0; bottom: 0; right: 0;
                font: inherit;
                color: inherit;
                text-align: center;
                z-index:1000;
                font-size: 24px !important;
            }
            .spn_important{
                color: #000;
                background-color: #e7515a;
                padding: 2px;
            }
            .form-control{
                padding: 5px !important;
                font-size: 12px !important;
                color:#000000;
            }
            input{
                color:#0a0a0f;
            }
            select{
                color:#0a0a0f;
            }
            .form-group label, label{
                color:#000000 !important;
            }
            .form-control-sm{
                padding: 5px !important;
                font-size: 12px !important;
                color:#000000;
            }
            .priority_btn_purple{
                color: #fcfcfc;
                background-color: #a31aff;
                padding: 2px;
            }
            .priority_btn_immed{
                color: #fff;
                background-color: #ff4d4d;
                padding: 2px;
            }
            .priority_btn_high{
                color: #fcfcfc;
                background-color: #2e5cb8;
                padding: 2px;
            }
            .priority_btn_noraml{
                color: #333;
                background-color: none;
                padding: 2px;
            }
            .spn_primary{
                color: #333;
                background-color: #ccf2ff;
                padding: 2px;
            }
             .spn_warning{
                color: #fff;
                background-color: #ff8c1a;
                padding: 2px;
            }
            .spn_success{
                color: #333;
                padding: 2px;
                background-color: #99ffbb;
            }
            #tr_heading th{
                background-color: #dff1f5 !important;
            }
            .tr_heading{
                background-color: #dff1f5 !important;
            }
            .label-important{
                background-color: #ff4d4d !important;
                color: #fcfcfc !important;
                font-weight: normal !important;
            }
            
            .label-warning{
                background-color: #ff8c1a !important;
                color: #fcfcfc !important;
                font-weight: normal !important;
            }
            .label-success{
                color: #333;
                background-color: #99ffbb !important;
                font-weight: normal !important;
            }
            .label-info{
                font-weight: normal !important;
                background-color: #2e5cb8 !important;
                color: white;
            }
.control-label{
    width: 100px !important;
    text-align: left !important;
}
.form-horizontal .controls{
    margin-left: 150px !important;
}
[data-title]:hover:after {
    opacity: 1;
    transition: all 0.1s ease 0.5s;
    visibility: visible;
}
[data-title]:after {
    content: attr(data-title);
    background-color: #EFEFEF;
    color: #111;
    font-size: 10px;
    position: absolute;
    padding: 1px 5px 2px 5px;
    bottom: -1.6em;
    left: 100%;
    white-space: nowrap;
    box-shadow: 1px 1px 3px #222222;
    opacity: 0;
    z-index: 99999;
    visibility: hidden;
}
.btn-mini{
    padding: 3px !important;
    font-size: 9px !important;
}

[data-title] {
    position: relative;
}
.title-task{
    color: #444;
}
.title-task a: hover{
    text-decoration: none !important;
}
        </style>
    </head>

    