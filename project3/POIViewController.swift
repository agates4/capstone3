//
//  POIViewController.swift
//  project3
//
//  Created by Aron Gates on 4/18/18.
//  Copyright © 2018 Aron Gates. All rights reserved.
//

import UIKit
import Alamofire
import SwiftyJSON
import PopupDialog

class POIViewController: UITableViewController  {

    var fruits : [[String]] = []
    // MARK: - Table view data source
    
    override func numberOfSections(in tableView: UITableView) -> Int {
        return 1
    }
    
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return fruits.count
    }
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let fruit = fruits[indexPath.row]
        print(fruit)
        
        // Prepare the popup assets
        let title = fruit[2]
        let message = "Description: " + fruit[0] + "\nLatitude: " + fruit[1] + "\nLongitude: " + fruit[3]
        
        // Create the dialog
        let popup = PopupDialog(title: title, message: message) {
            print("popped down")
        }
        self.present(popup, animated: true) {
            print("popped up")
        }
    }
    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "LabelCell", for: indexPath)
        
        let fruit = fruits[indexPath.row]
        cell.textLabel?.text = fruit[2]
        cell.detailTextLabel?.text = fruit[0]
        
        return cell
    }
    
}
