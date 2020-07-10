
INSERT INTO `crm_fields` (`id`, `group_id`, `order`, `name`, `description`, `type`, `active`) VALUES
(1, 1, 0, 'Company Name', NULL, 'text', 1),
(2, 1, 1, 'Webpage', NULL, 'text', 1),
(3, 1, 2, 'Campaign', NULL, 'select', 1),
(4, 1, 3, 'Industry', NULL, 'select', 1),
(5, 2, 0, 'Facebook', NULL, 'text', 1),
(6, 2, 1, 'Twitter', NULL, 'text', 1),
(7, 2, 2, 'Google+', NULL, 'text', 0),
(8, 2, 3, 'Linkedin', NULL, 'text', 1);


--
-- Zrzut danych tabeli `crm_fields_groups`
--

INSERT INTO `crm_fields_groups` (`id`, `name`, `color`, `order`, `active`) VALUES
(1, 'Company', '#FF0000', 5, 0),
(2, 'Social', '#044af5', 1, 1);


--
-- Zrzut danych tabeli `crm_fields_options`
--

INSERT INTO `crm_fields_options` (`id`, `field_id`, `value`) VALUES
(1, 3, 'Not Defined'),
(2, 3, 'Weekly Promotion'),
(3, 3, 'Christmas special offer'),
(4, 3, 'Special Products'),
(5, 4, 'Not Defined'),
(6, 4, 'Hosting'),
(7, 4, 'Resellers'),
(8, 4, 'Domains'),
(9, 4, 'IT'),
(10, 4, 'Other');


--
-- Zrzut danych tabeli `crm_followup_types`
--

INSERT INTO `crm_followup_types` (`id`, `name`, `color`, `order`, `active`) VALUES
(1, 'Meeting', '#ba3636', 0, 1),
(2, 'Call', '#8abe1c', 1, 1);



--
-- Zrzut danych tabeli `crm_resources_statuses`
--

INSERT INTO `crm_resources_statuses` (`id`, `name`, `color`, `order`, `active`) VALUES
(1, 'New', '#00c444', 2, 1),
(2, 'Active', '#0a6afa', 2, 1),
(3, 'Urgent', '#c40000', 5, 1),
(4, 'Closed', '#3d3d3d', 2, 1);


--
-- Add pre defined types
--
INSERT INTO `crm_resources_types` 
(`id`, `name`,      `order`, `color`, `icon`, `active`, `show_in_nav`, `show_in_submenu`, `show_in_dashboard`, `created_at`, `updated_at`) 
VALUES
(1,    'Lead',      0,  '#00c444', 'fa fa-star-o', 1,          1,              1,                  1,                  NOW(),      NOW()   ),
(2,    'Potential', 1,  '#00c444', 'fa fa-star',   1,          1,              1,                  1,                  NOW(),      NOW()   );


--
-- Zrzut danych tabeli `crm_settings`
--

INSERT INTO `crm_settings` (`admin_id`, `name`, `value`) VALUES
(0, 'assign_admin', '1'),
(0, 'followups_per_day', '0'),
(0, 'quotations_enable', '1'),
(0, 'fields_map', '{"static":{"firstname":"name","address1":"","lastname":"","address2":"","companyname":"","city":"","email":"email","state":"","postcode":"","phonenumber":"phone"}}');