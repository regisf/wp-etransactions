<?php

if (!class_exists('ETransaction_Plugin_Activation')) {
    class ETransaction_Plugin_Activation
    {
        private function installDatabase()
        {

        }

        private function installSettings()
        {

        }

        public function doActivation()
        {
            $this->installDatabase();
            $this->installSettings();
        }
    }
}