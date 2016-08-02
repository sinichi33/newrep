#!/bin/bash
#this script modified by Sutrisno 14-3-2016 to use config file
source $MAGENTO_ROOT/shell/config.cfg

FILES=$MAGENTO_ROOT/var/urapidflow/import/EC_ODI*.csv

echo "Prepare Reserved Stock"
mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_STOCKRESERVED.sql

for f in $FILES
do
  echo "Processing $f file..."
  # take action on each file. $f store current file name
  #cat $f
  if [[ "${f}" == *EC_ODI_A001* ]]; then      
  	  ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A001.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/  	
      echo "Import A001"  
	  
  elif [[ "${f}" == *EC_ODI_A314* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A314.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A314"  
	  
  elif [[ "${f}" == *EC_ODI_A319* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A319.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A319"    	  

  elif [[ "${f}" == *EC_ODI_A322* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A322.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A322"    

  elif [[ "${f}" == *EC_ODI_A327* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A327.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A327"

  elif [[ "${f}" == *EC_ODI_A332* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A332.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A332"   

  elif [[ "${f}" == *EC_ODI_A336* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A336.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A336"   		
	  
  elif [[ "${f}" == *EC_ODI_A346* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A346.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A346"
	  
  elif [[ "${f}" == *EC_ODI_A422* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A422.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A422"    		  
	  
  elif [[ "${f}" == *EC_ODI_A433* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_A433.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import A433" 	  	     

  elif [[ "${f}" == *EC_ODI_H001* ]]; then      
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H001.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/  	
      echo "Import H001"

  elif [[ "${f}" == *EC_ODI_H014* ]]; then      
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H014.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/  	
      echo "Import H014"

  elif [[ "${f}" == *EC_ODI_H302* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H302.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H302"     	  

  elif [[ "${f}" == *EC_ODI_H305* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H305.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H305"    
  	  
  elif [[ "${f}" == *EC_ODI_H308* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H308.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H308"   

  elif [[ "${f}" == *EC_ODI_H312* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H312.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H312"	  

  elif [[ "${f}" == *EC_ODI_H326* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H326.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H326"	    	

  elif [[ "${f}" == *EC_ODI_H327* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H327.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H327"   

  elif [[ "${f}" == *EC_ODI_H328* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H328.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H328"    	  

  elif [[ "${f}" == *EC_ODI_H374* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H374.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H374"    		  

  elif [[ "${f}" == *EC_ODI_H384* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_H384.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import H384"  

  elif [[ "${f}" == *EC_ODI_K001* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_K001.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import K001" 
	  
  elif [[ "${f}" == *EC_ODI_L001* ]]; then      
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_L001.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/  	
      echo "Import L001"
 
  elif [[ "${f}" == *EC_ODI_S001* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_S001.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import S001" 

  elif [[ "${f}" == *EC_ODI_T001* ]]; then      
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T001.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/  	
      echo "Import T001"	

  elif [[ "${f}" == *EC_ODI_T302* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T302.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T302"   	

  elif [[ "${f}" == *EC_ODI_T303* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T303.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T303"

  elif [[ "${f}" == *EC_ODI_T304* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T304.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T304"    

  elif [[ "${f}" == *EC_ODI_T305* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T305.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T305"   

  elif [[ "${f}" == *EC_ODI_T314* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T314.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T314"    	  

  elif [[ "${f}" == *EC_ODI_T317* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T317.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T317"   		  
	  
  elif [[ "${f}" == *EC_ODI_T318* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T318.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T318"	  

  elif [[ "${f}" == *EC_ODI_T322* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T322.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T324"    

  elif [[ "${f}" == *EC_ODI_T324* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_T324.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import T324"    	

  elif [[ "${f}" == *EC_ODI_O001* ]]; then
      ln -s -f "$f" /tmp/temp_SAPInventoryImport.txt
      mysql -u $user -h $host -p$pass $db < /var/public/www.ruparupa.com/current/shell/sql/import_inventory_O001.sql
      rm -f /tmp/temp_SAPInventoryImport.txt
      mv "$f" $MAGENTO_ROOT/var/urapidflow/import/archive/    
      echo "Import O001" 	    
	     	
  else
      echo "No"
  fi

  echo "Update Global Stock"
  mysql -u $user -h $host -p$pass $db <  /var/public/www.ruparupa.com/current/shell/sql/import_inventory_UPDATEGLOBAL.sql
  
  echo "Update Stock Completed"

done

  mysql -u $user -h $host -p$pass -e "truncate table ruparupadb.temp_SAPInventoryImport"
