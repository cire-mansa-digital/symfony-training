import { startStimulusApp } from '@symfony/stimulus-bridge';
import FormCollectionController from './controllers/form-collection_controller.js';

export const app = startStimulusApp(import.meta.webpackContext('@symfony/stimulus-bridge/lazy-controller-loader!./controllers', {
    recursive: true,
    regExp: /\.([jt])sx?$/,
}));

// Enregistrement manuel du contrôleur
app.register('form-collection', FormCollectionController);
