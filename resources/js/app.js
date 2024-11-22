import './bootstrap';
import Swal from 'sweetalert2';


window.Swal = Swal;

$(document).ready(function () {
    $('.select2.exemplo').select2(
        {
        "language": "pt-BR"
      }
    );
});