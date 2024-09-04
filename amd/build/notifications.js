define(['jquery', 'message_popup/notification_repository'],
    function($, NotificationRepository) {
    let notificationToggler = document.querySelector('[data-drawer="drawer-notifications"]');
    let countContainer = notificationToggler.querySelector('[data-region="count-container"]');
    


    //Api de notificações
    function getNotifications() {
        const userid = document.querySelector('[data-userid]').getAttribute('data-userid');

        NotificationRepository.query({
            useridto: userid, // ID real do usuário
            limit: 10,
            offset: 0
        }).done(function(data) {
            console.log(data);
        }).fail(function(error) {
            console.error(error);
        });

        NotificationRepository.countUnread({
            useridto: userid,
        }).done(function(data) {
            if (data) {
                countContainer.innerHTML = data;
            }
        }).fail(function(error) {
            console.log(error);
        })
    }

    return {
        init: function() {
            getNotifications();

        }
    };

});
