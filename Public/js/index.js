$(function(){
    H_qqServer={};
    H_qqServer.clickOpenServer = function () {
        $('.qq-o').click(function(){
            $('.qq').animate({
                left: '-50'
            },400);
            $('.qq-content').animate({
                left: '0',
                opacity: 'show'
            }, 800 );
        });
        $('.qq-c').click(function(){
            $('.qq').animate({
                left: '0',
                opacity: 'show'
            },400);
            $('.qq-content').animate({
                left: '-200',
            }, 800 );
        });
    };
    H_qqServer.run= function () {
        this.clickOpenServer();
    };
    H_qqServer.run();
});