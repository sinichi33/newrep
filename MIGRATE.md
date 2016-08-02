## Magento extensions profile data config list migration (excluding system config data)

# Rapidflow Profiles: 
- Import EAV (attribute data)
- Import EAXP (attribute data)
- Import EAS (list of attribute set)
- Import EAG (mapping attribute groups to attribute set)
- Import EASI (mapping attributes to attribute set and groups)
- Import EAO (for attributes option list)
- Import Product (the name needs to be exactly like this because it's needed in the Icube Import Controller for cronjob. Contains profile and config for importing product and main images. Need to maintain the Import Columns information)
- Import CCP (assigning simple products to categories)
- Import CPI (assigning images to products)

# Xtento Order Export
- Export Invoice (inside menu Export Profiles)
- CreateSOB2C (inside menu Export Destinations)

# Amasty Order Attributes
- all attributes inside menu: Sales > Manage Order Attributes

# RMA Attributes
- all attributes (it contains new attributes and default magento rma attributes that has been modified) inside menu: Sales > RMA > Manage Items Attributes
