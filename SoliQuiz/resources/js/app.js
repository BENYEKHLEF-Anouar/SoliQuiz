import './bootstrap';
import Alpine from 'alpinejs';
import 'preline';

import adminQcmBank from './components/admin/adminQcmBank';
import qcmLibrary from './components/formateur/qcmLibrary';
import qcmBuilder from './components/formateur/qcmBuilder';
import resultsFilter from './components/formateur/resultsFilter';
import studentChartData from './components/formateur/studentChartData';
import etudiantLibrary from './components/etudiant/etudiantLibrary';
import qcmForm from './components/etudiant/qcmForm';
import chatbot from './components/common/chatbot';
import aiExplain from './components/etudiant/aiExplain';

window.Alpine = Alpine;

Alpine.data('adminQcmBank', adminQcmBank);
Alpine.data('qcmLibrary', qcmLibrary);
Alpine.data('qcmBuilder', qcmBuilder);
Alpine.data('resultsFilter', resultsFilter);
Alpine.data('studentChartData', studentChartData);
Alpine.data('etudiantLibrary', etudiantLibrary);
Alpine.data('qcmForm', qcmForm);
Alpine.data('chatbot', chatbot);
Alpine.data('aiExplain', aiExplain);

// Standard initialization for Vite-loaded scripts (deferred by default)
Alpine.start();
