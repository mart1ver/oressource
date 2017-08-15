/*
 Oressource
 Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as
 published by the Free Software Foundation, either version 3 of the
 License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Permet d'acceder aux parametres de l'url (query/search/get parameters)
 * sous la forme d'un objet js.
 */
function process_get() {
  const val = { };
  const query = new URLSearchParams(window.location.search.slice(1)).entries();
  for (const pair of query) {
    val[pair[0]] = pair[1];
  }
  return val;
}

function set_datepicker(data) {
  const startDate = moment(data.date1, 'DD-MM-YYYY');
  const endDate = moment(data.date2, 'DD-MM-YYYY');
  const now = moment();
  const options = {
    rawStart: startDate,
    rawEnd: endDate,
    startDate: startDate.format('DD/MM/YYYY'),
    endDate: endDate.format('DD/MM/YYYY'),
    minDate: '01/01/2010',
    maxDate: '12/31/2020',
    dateLimit: { days: 800 },
    showDropdowns: true,
    showWeekNumbers: true,
    timePicker: false,
    timePickerIncrement: 1,
    timePicker12Hour: true,
    ranges: {
      "Aujoud'hui": [ now, now ],
      'hier': [ now.subtract(1, 'days'), now.subtract(1, 'days') ],
      '7 derniers jours': [ now.subtract(6, 'days'), now ],
      '30 derniers jours': [ now.subtract(29, 'days'), now ],
      'Ce mois': [
        now.startOf('month'),
        now.endOf('month')
      ],
      'Le mois deriner': [
        now.subtract(1, 'month').startOf('month'),
        now.subtract(1, 'month').endOf('month')
      ]
    },
    opens: 'left',
    buttonClasses: [ 'btn btn-default' ],
    applyClass: 'btn-small btn-primary',
    cancelClass: 'btn-small',
    format: 'DD/MM/YYYY',
    separator: ' to ',
    locale: {
      applyLabel: 'Appliquer',
      cancelLabel: 'Annuler',
      fromLabel: 'Du',
      toLabel: 'Au',
      customRangeLabel: 'Période libre',
      daysOfWeek: [ 'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa' ],
      monthNames: [ 'Janvier', 'Fevrier', 'Mars'
                , 'Avril', 'Mai', 'Juin'
                , 'Juillet', 'Aout', 'Septembre'
                , 'Octobre', 'Novembre', 'Decembre' ],
      firstDay: 1
    }
  };
  return options;
}

function cb(start, end, label) {
  $('#reportrange span').html(`${start.format('DD, MMMM, YYYY')} - ${end.format('DD, MMMM, YYYY')}`);
}

function bind_datepicker(options, data, url) {
  const picker_element = $('#reportrange');
  picker_element.daterangepicker(options, cb);
  $('#reportrange span').html(`${options.rawStart.format('DD/MM/YYYY')} - ${options.rawEnd.format('DD/MM/YYYY')}`);
  picker_element.on('apply.daterangepicker', (ev, picker) => {
    const start = picker.startDate.format('DD-MM-YYYY');
    const end = picker.endDate.format('DD-MM-YYYY');
    window.location.href = `./${url}.php?date1=${start}&date2=${end}&numero=${data.numero}`;
  });
}
