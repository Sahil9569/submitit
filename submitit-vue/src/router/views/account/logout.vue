<script>
import axios from 'axios';
// import router from '../../index'
export default {
  data() {
    return {
      url: process.env.VUE_APP_URL
    }
  },
  created() {
    this.logout();
  },
  activated()
  {
    this.logout()
  },
  methods: {
    logout() {
      axios.post(this.url + 'api/logout').then(() => {
        localStorage.removeItem('user');
        localStorage.removeItem('logged');
        localStorage.removeItem('authRequired');
        localStorage.removeItem('email');
        localStorage.removeItem('userType');
        localStorage.removeItem('customUser');
        this.$router.push('/login');
      }).catch(error => {
        console.log(error);
      });

    }
  }
}
</script>