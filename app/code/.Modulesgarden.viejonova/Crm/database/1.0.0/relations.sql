
--
-- foreign keys for `crm_fields_data`
--
ALTER TABLE `crm_fields_data`
  ADD CONSTRAINT `crm_fields_data_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `crm_fields` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `crm_fields_data_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- foreign keys for `crm_fields_data_options`
--
ALTER TABLE `crm_fields_data_options`
  ADD CONSTRAINT `crm_fields_data_options_ibfk_1` FOREIGN KEY (`field_data_id`) REFERENCES `crm_fields_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `crm_fields_data_options_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `crm_fields_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- foreign keys for `crm_fields_options`
--
ALTER TABLE `crm_fields_options`
  ADD CONSTRAINT `crm_fields_options_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `crm_fields` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- foreign keys for `crm_fields_validators`
--
ALTER TABLE `crm_fields_validators`
  ADD CONSTRAINT `crm_fields_validators_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `crm_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- foreign keys for `crm_followups`
--
ALTER TABLE `crm_followups`
  ADD CONSTRAINT `crm_followups_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `crm_followups_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `crm_followup_types` (`id`);

--
-- foreign keys for `crm_followup_reminders`
--
ALTER TABLE `crm_followup_reminders`
  ADD CONSTRAINT `crm_followup_reminders_ibfk_1` FOREIGN KEY (`followup_id`) REFERENCES `crm_followups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- foreign keys for `crm_logs`
--
ALTER TABLE `crm_logs`
  ADD CONSTRAINT `crm_logs_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- foreign keys for `crm_email_logs`
--
ALTER TABLE `crm_email_logs`
  ADD CONSTRAINT `crm_email_logs_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `crm_email_logs_ibfk_3` FOREIGN KEY (`followup_id`) REFERENCES `crm_followups` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `crm_email_logs_ibfk_4` FOREIGN KEY (`reminder_id`) REFERENCES `crm_followup_reminders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- foreign keys for `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD CONSTRAINT `crm_notes_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- foreign keys for `crm_resources`
--
ALTER TABLE `crm_resources`
  ADD CONSTRAINT `crm_resources_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `crm_resources_statuses` (`id`) ON UPDATE CASCADE,
  ADD INDEX `type_id` (`type_id`),
  ADD CONSTRAINT `crm_resources_ibfk_6` FOREIGN KEY (`type_id`) REFERENCES `crm_resources_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- foreign keys for `crm_resources_clients`
--
ALTER TABLE `crm_resources_clients`
  ADD CONSTRAINT `crm_resources_clients_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- foreign keys for `crm_resource_files`
--
ALTER TABLE `crm_resources_files`
  ADD CONSTRAINT `crm_resources_files_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- foreign keys for `crm_campaigns_admins`
--
ALTER TABLE `crm_campaigns_admins`
  ADD CONSTRAINT `crm_campaigns_admins_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `crm_campaigns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- foreign keys for `crm_campaigns_resources`
--
ALTER TABLE `crm_campaigns_resources`
  ADD CONSTRAINT `crm_campaigns_resources_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `crm_campaigns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `crm_campaigns_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- foreign keys for `crm_notifications_admins`
--
ALTER TABLE `crm_notifications_admins`
  ADD CONSTRAINT `crm_notifications_admins_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `crm_notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- foreign keys for `crm_mass_message_pendings`
--
ALTER TABLE `crm_mass_message_pendings`
  ADD CONSTRAINT `crm_mass_message_pendings_ibfk_1` FOREIGN KEY (`mass_message_config_id`) REFERENCES `crm_mass_message_configs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `crm_mass_message_pendings_ibfk_3` FOREIGN KEY (`resource_id`) REFERENCES `crm_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;