
        function toggleSessionField() {
            var hourWise = document.getElementById('hour_wise').checked;
            var halfDay = document.getElementById('half_day').checked;
            var sessionWrapper = document.getElementById('session_wrapper');
            var sessionField = document.getElementById('session');
            var hourWiseWrapper = document.getElementById('hour_wise_wrapper');

            if (halfDay) {
                sessionWrapper.style.display = 'block';
                sessionField.setAttribute('required', 'required');
            } else {
                sessionWrapper.style.display = 'none';
                sessionField.removeAttribute('required');
            }

            if (hourWise) {
                hourWiseWrapper.style.display = 'block';
            } else {
                hourWiseWrapper.style.display = 'none';
            }
        }

        function onlyOneCheckbox(checkbox) {
            var checkboxes = document.getElementsByClassName('exclusive-checkbox');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] !== checkbox) {
                    checkboxes[i].checked = false;
                }
            }
            toggleSessionField();
        }

        function selectHourBox(box) {
            // Toggle selected hour box
            box.classList.toggle('selected-hour');

            // Collect all selected hours
            var selectedHours = [];
            var boxes = document.getElementsByClassName('hour-box');
            for (var i = 0; i < boxes.length; i++) {
                if (boxes[i].classList.contains('selected-hour')) {
                    selectedHours.push(boxes[i].innerHTML);
                }
            }

            // Set selected hours in hidden input field as a comma-separated string
            document.getElementById('selected_hour').value = selectedHours.join(',');
        }
    