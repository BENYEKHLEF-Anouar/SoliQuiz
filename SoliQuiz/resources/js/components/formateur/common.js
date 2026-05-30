export { secureFetch, dispatchToast, dispatchConfirm } from '../common/helpers';

import { secureFetch, dispatchToast, dispatchConfirm } from '../common/helpers';

export async function toggleQcmStatusApi(qcm) {
    try {
        const response = await secureFetch(`/formateur/qcm/${qcm.id}/toggle`, {
            method: 'PATCH'
        });
        if (response.ok) {
            const data = await response.json();
            qcm.statut = data.statut;
            dispatchToast(data.message || 'Statut mis à jour', 'success');
            return true;
        }
    } catch (error) {
        dispatchToast('Erreur lors de la mise à jour', 'error');
    }
    return false;
}

export function deleteQcmWithConfirm(id, onDeleted) {
    dispatchConfirm({
        title: 'Supprimer ce QCM ?',
        message: 'Cette action est irréversible et supprimera définitivement toutes les données et tentatives liées à cette évaluation.',
        type: 'danger',
        confirmText: "Supprimer l'actif",
        onConfirm: async () => {
            const success = await executeDeleteQcmApi(id);
            if (success && typeof onDeleted === 'function') {
                onDeleted();
            }
        }
    });
}

export async function executeDeleteQcmApi(id) {
    try {
        const response = await secureFetch(`/formateur/qcm/${id}`, {
            method: 'DELETE'
        });
        if (response.ok) {
            dispatchToast('QCM supprimé définitivement', 'success');
            return true;
        }
    } catch (error) {
        dispatchToast('Erreur lors de la suppression', 'error');
    }
    return false;
}
