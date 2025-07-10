<template>
    <footer class="footer">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">Copyright © {{ new Date().getFullYear() }} Submitit</div>
          <div class="col-sm-6">
            <div class="text-sm-end d-none d-sm-block"><a href="https://midriffinfosolution.org/" target="_blank">Designed & Developed by MISPL</a></div>
          </div>
        </div>
      </div>
      <div class="main-loader-card fullWdthLoader loaders" :class="{'d-none':$store.state.isSubscribe}"><div class="loader2"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div></div>
            <!-- toastHideStyle -->
            <div class="toast-modal-area toast-modal-custom toast-top-right ">
              <div v-if="$store.state.isAccepted" class="toast show toast-success"  aria-live="polite">
                <div class="success-image-style image-style-toast">
                  <img src="@/assets/images/check-success-icon.png" alt="check">
                </div>
                <div class="toast-message">{{$store.state.notificationData }}</div>
              </div>
                <div v-if="$store.state.isDeclined" class="toast show toast-danger"  aria-live="polite">
                  <div class="success-image-style image-style-toast">
                    <img src="@/assets/images/error-cancel.png" alt="check">
                  </div>
                  <div class="toast-message">{{$store.state.notificationData}}</div>
                </div>
			</div>
    </footer>
    <!-- end footer -->
  </template>
  <script>
  </script>  