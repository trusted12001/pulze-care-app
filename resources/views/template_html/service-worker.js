self.addEventListener("install", function (event) {
  event.waitUntil(
    caches.open("appoinx-site").then(function (cache) {
      return cache.addAll([
        "/",
        "/index.html",
        "/assets/css/style.css",
        "/add-card.html",
        "/appoinment-schedule.html",
        "/booking-confirmed.html",
        "/camera-open.html",
        "/cancel-appoinment.html",
        "/cancel-successfully.html",
        "/chat-box.html",
        "/chat-list.html",
        "/chat-session-end.html",
        "/customer-service-chat.html",
        "/customer-service.html",
        "/doctor-profile.html",
        "/edit-profile.html",
        "/faq-page.html",
        "/forget-password.html",
        "/help-support.html",
        "/home.html",
        "/invite-friends.html",
        "/language-setting.html",
        "/legal-policies.html",
        "/make-payment.html",
        "/my-appoinment.html",
        "/notification-setting.html",
        "/patient-details.html",
        "/profile-settings.html",
        "/reset-successfully.html",
        "/session-review.html",
        "/sign-in.html",
        "/sign-up.html",
        "/top-doctor-search-loading.html",
        "/top-doctor-search-not-found.html",
        "/verify-otp.html",
        "/video-call-ringing.html",
        "/video-call-session-end.html",
        "/video-call-talking.html",
        "/voice-call-ringing.html",
        "/voice-call-session-end.html",
        "/voice-call-talk-recording.html",
        "/voice-call-talk.html",
      ]);
    })
  );
});

self.addEventListener("fetch", function (event) {
  event.respondWith(
    caches.match(event.request).then(function (response) {
      return response || fetch(event.request);
    })
  );
});
